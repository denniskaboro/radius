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

    function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%ad, %hh, %im and %ss');
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
            "radpostauth",
            "sms_check",
            "hotspot_user"
        ];
        foreach ($table as $tab) {
            $this->Delete($tab, "username='$username'");
        }
    }

    function userCheck($username)
    {
        $ass = $this->Select("*", "radcheck", "username='$username' and attribute='Expire-After'");

        if ($ass->num_rows > 0) {
            $a = $ass->fetch_assoc();
            $assign_time = $a['value'];
            $used_time = $this->RowFetch("IFNULL( MAX(TIME_TO_SEC(TIMEDIFF(NOW(), acctstarttime))),0) as total_session", "radacct", "username='$username' ORDER BY acctstarttime ASC LIMIT 1");
            if ($used_time['total_session'] > 0) {
                if ($used_time['total_session'] >= $assign_time) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function checkPayment($CheckoutRequestID)
    {
        $row = $this->Select("*", "hotspot_payment", "checkoutRequestID='$CheckoutRequestID'");
        if ($row->num_rows > 0) {
            $f = $row->fetch_assoc();
            $username = $_SESSION['mac'];
            $mobile = $f['number'];

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
                $time = $g['duration'];
                $data = $g['data'];

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
                        ]
                    )
                );
                if ($newDate > 0) {
                    $this->dataInsert("radcheck",
                        array(
                            [
                                "username" => $username,
                                "attribute" => "Expire-After",
                                "op" => ":=",
                                "value" => $newDate
                            ]
                        )
                    );
                }
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
                $this->dataInsert("hotspot_user", array(
                        [
                            "username" => $username,
                            "mobile" => $mobile,
                            "duration" => $time,
                            "bandwidth" => $data
                        ]
                    )
                );
                if ($data == "") {
                    $data = "Unlimited";
                }
                if ($time == "") {
                    $time = "Unlimited";
                }

                $message = "Dear Customer, Your payment has been completed. Your current Plan data limit is $data and it will expire in $time";
                $res = $this->SendSMS("$message", "$mobile" . ",");
                $this->log("$username", $res['status'] . ":" . $res['message']);

            } else {
                $result = array("status" => "success", "message" => $f['resultDesc']);
            }
        } else {
            $result = array("status" => "error", "message" => "No payment found need to recheck. Please wait for 12s");
        }
        return $result;
    }

    function SendSMS($message, $mobile = null, $user = null)
    {
        $sms_count = strlen($message);
        if ($sms_count < 70) {
            $sms_num = 1;
        } elseif ($sms_count >= 70 and $sms_count < 134) {
            $sms_num = 2;
        } elseif ($sms_count >= 134 and $sms_count < 200) {
            $sms_num = 3;
        } elseif ($sms_count >= 200 and $sms_count < 266) {
            $sms_num = 4;
        } else {
            $sms_num = 5;
        }
        if ($user != null) {
            $uu = explode(",", $user);
            foreach ($uu as $username) {
                $m = $this->RowFetch("mobile", "clients", "username='$username'");
                $mobile = $m['mobile'];
                $p_m = "/^0/i";
                $mobile = preg_replace($p_m, "", $mobile);
                $total[] = array(
                    "Number" => "254" . $mobile,
                    "Text" => "$message"
                );
            }
        }

        if ($mobile != null) {
            $uu = explode(",", $mobile);
            foreach ($uu as $mobile) {
                $total[] = array(
                    "Number" => $mobile,
                    "Text" => "$message"
                );
            }
        }
        //SMS Send
        $sql = "SELECT * FROM `smsgateway` WHERE `supplier_id`='Admin'";
        $s = mysqli_query($this->connection, $sql);
        $row = mysqli_num_rows($s);
        if ($row > 0) {
            $ss = mysqli_fetch_array($s);
            $api_url = $ss['url'];
            $SenderId = $ss['SenderId'];
            $ClientId = $ss['ClientId'];
            $ApiKey = $ss['ApiKey'];
            $AccessKey = $ss['AccessKey'];
        } else {
            return json_encode(array("status" => "error", "message" => "No Gateway Found!"));
        }

        $data = array(
            "SenderId" => $SenderId,
            "IsUnicode" => true,
            "IsFlash" => true,
            "MessageParameters" => $total,
            "ApiKey" => $ApiKey,
            "ClientId" => $ClientId
        );
        $postData = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "AccessKey: " . $AccessKey,
                "Content-Type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return json_encode(array("status" => "error", "message" => "$err"));
        } else {
            $result = json_decode($response);
            if ($result->ErrorCode == 0) {
                $d = $result->Data;
                foreach ($d as $dd) {
                    if ($dd->MessageErrorCode == 0) {
                        $number = $dd->MobileNumber;
                        $in = "INSERT INTO sms (`supplier_id`,`mobile`,`message`,`sms_count`) VALUES('Admin','$number','$message','$sms_num')";
                        mysqli_query($this->connection, $in);
                    }
                }
                return json_encode(array("status" => "success", "message" => "SMS has been send successfully "));
            } else {
                return json_encode(array("status" => "error", "message" => "$err"));
            }
        }

    }
}

?>
