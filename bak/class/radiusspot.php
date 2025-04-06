<?php
namespace RadiusSpot;

class RadiusSpot
{

    protected $connection;

    function __construct($con)
    {
        $this->connection = $con;
    }

    function log($user, $work)
    {
        $client = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO log (username,type,remote_host,work)VALUES('$user','Admin','$client','$work')";
        mysqli_query($this->connection, $sql);
    }

    public function RowFetch($select, $from, $where = null)
    {
        $q = $this->Select($select, $from, $where);
        $fetch = $q->fetch_assoc();
        return $fetch;
    }

    public function Select($select, $from, $where = null)
    {
        if ($where != null) {
            $stmt = "SELECT {$select} FROM {$from} WHERE {$where}";
        } else {
            $stmt = "SELECT {$select} FROM {$from}";
        }

        $result = $this->connection->query($stmt);
        return $result;

    }

    public function AllRowFetch($select, $from, $where = null)
    {
        $q = $this->Select($select, $from, $where);
        $fieldinfo = $q->fetch_all(MYSQLI_ASSOC);
        return $fieldinfo;
    }

    public function dataInsert($table, $data)
    {
        foreach ($data as $values) {
            $valueData = array();
            $valueCol = array();
            foreach ($values as $col => $value) {
                $valueData[] = $value;
                $valueCol[] = $col;
            }
            $data = implode("','", $valueData);
//            $data = $this->RealStr($data);
            $col = implode(',', $valueCol);

            $sql = "INSERT INTO $table ($col) VALUES ('" . $data . "')";

            if ($this->connection->query($sql) === TRUE) {
                $last_id = $this->connection->insert_id;
                $result = array("id" => $last_id, "status" => "success", "message" => "Data successfully saved.");
            } else {
                $msg = "Error: " . $this->connection->error;
                $result = array("status" => "error", "message" => "$msg");
            }
        }
        return $result;
    }

    public function arrayInsert($data)
    {
        $res = [];
        foreach ($data as $inData) {
            $table = $inData['tbl_name'];
            $tbl_value = $inData['sendData'];
            foreach ($tbl_value as $values) {
                $valueData = array();
                $valueCol = array();
                foreach ($values as $col => $value) {
                    $valueData[] = $value;
                    $valueCol[] = $col;
                }
                $data = implode("','", $valueData);
//                $data = $this->RealStr($data);
                $col = implode(',', $valueCol);

                $sql = "INSERT INTO $table ($col) VALUES ('" . $data . "')";

                if ($this->connection->query($sql) === TRUE) {
                    $last_id = $this->connection->insert_id;
                    $result = array("id" => $last_id, "status" => "success", "message" => "New Record Inserted Successfully");
                } else {
                    $msg = "Error:" . $this->connection->error;
                    $result = array("status" => "error", "message" => "$msg");
                }
            }
        }
        return $result;
    }

    public function dataUpdate($table, $data, $where = null)
    {
        $valueData = array();
        foreach ($data as $col => $value) {
            $valueData[] = "$col = '$value'";
        }
        $data = implode(",", $valueData);
//        $data = $this->RealStr($data);
        if ($where != null) {
            $sql = "UPDATE $table SET $data WHERE $where";
        } else {
            $sql = "UPDATE $table SET $data";
        }
        if ($this->connection->query($sql) === TRUE) {
            $result = array("status" => "success", "message" => "Your data was successfully updated");
        } else {
            $msg = "Error:" . $this->connection->error;
            $result = array("status" => "error", "message" => "$msg");
        }
        return $result;
    }

    public function Delete($from, $where, $backup = 0)
    {
        if ($where != null) {
            if ($backup == 1) {
                $this->userBackup("$from", "$from", "$where");
            }
            $stmt = "DELETE FROM $from WHERE $where";
        } else {
            if ($backup == 1) {
                $this->userBackup("$from", "$from");
            }
            $stmt = "DELETE FROM $from";
        }
        if ($this->connection->query($stmt) === TRUE) {
            $result = array("status" => "success", "message" => "Your data was successfully deleted");
        } else {
            $result = array("status" => "error", "message" => "something went wrong");
        }
        return $result;

    }

    public function userRemove($username)
    {
        $table = [
            "radcheck",
            "radreply",
            "radacct",
            "clients",
            "radusergroup",
            "user_balance",
            "radpostauth"
        ];
        foreach ($table as $tab) {
            $this->Delete($tab, "username='$username'");
        }
    }

    function userCheck($username)
    {
        $used_time = $this->RowFetch("IFNULL( MAX(TIME_TO_SEC(TIMEDIFF(NOW(), acctstarttime))),0)", "radacct", "username='$username' ORDER BY acctstarttime LIMIT 1");
        $ass = $this->Select("*", "radcheck", "username='$username' and attribute='Expire-After'");
        if ($ass->num_rows > 0) {
            $a = $ass->fetch_assoc();
            $assign_time = $a['value'];
        } else {
            return false;
        }

        if ($used_time > 0) {
            if ($used_time >= $assign_time) {
                return false;
            } else {
                return true;
            }
        }

        if ($ass->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function checkPayment($CheckoutRequestID)
    {
        $row = $this->Select("*", "hotspot_payment", "checkoutRequestID='$CheckoutRequestID'");
        if ($row->num_rows > 0) {
            $f = $row->fetch_assoc();
            $username = $_SESSION['mac'];
            $this->dataUpdate("hotspot_payment", array("username" => $username), "checkoutRequestID='$CheckoutRequestID'");

            if ($f['resultCode'] == 0) {
                $amount = intval($f['amount']);
                $trxId = $f['trxId'];

                $this->userRemove($username);

                $this->dataInsert("user_balance", array(
                        [
                            "username" => $username,
                            "debit" => $amount,
                            "transactionId" => $trxId
                        ]
                    )
                );

                $group_id = $_SESSION['group_id'];
                $g = $this->RowFetch("*", "groups", "id='$group_id'");
                $duration = explode(" ", $g['duration']);

                if ($duration[1] == 'Minute') {
                    $newDate = $duration[0] * 60;
                } elseif ($duration[1] == 'Hour') {
                    $newDate = $duration[0] * 60 * 60;
                } elseif ($duration[1] == 'Days') {
                    $newDate = $duration[0] * 60 * 60 * 24;
                } else {
                    $newDate = $duration[0] * 60 * 60 * 24 * 30;
                }

                $this->dataInsert("radcheck",
                    array(
                        [
                            "username" => $username,
                            "attribute" => "Cleartext-Password",
                            "op" => ":=",
                            "value" => $username
                        ],
                        [
                            "username" => $username,
                            "attribute" => "Expire-After",
                            "op" => ":=",
                            "value" => $newDate
                        ]
                    )
                );
                $this->dataInsert("radusergroup",
                    array(
                        [
                            "username" => $username,
                            "groupname" => $group_id
                        ]
                    )
                );

                $result = array("status" => "success", "message" => $f['resultDesc']);
                $this->log("$username", "Hotspot user created");
            } else {
                $result = array("status" => "success", "message" => $f['resultDesc']);
            }
        } else {
            $result = array("status" => "error", "message" => "No payment found need to recheck. Please wait for 12s");
        }
        return $result;
    }
}

?>
