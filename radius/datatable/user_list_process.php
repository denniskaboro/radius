<?php

use RadiusSpot\RadiusSpot;

include('../session.php');
include "../config/data.php";
require_once "../class/radiusspot.php";
$API = new RadiusSpot($con);

$wh = $_SESSION['username'];

if (isset($_POST['userType'])) {
    $data = array();

    $user_type = $_POST['userType'];

    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];// Rows display per page

    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = "clients." . $_POST['columns'][$columnIndex]['data'];

    $whe = "clients.username !=''";

    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    if ($user_type == "deactive") {
        $whe .= " and radcheck.attribute='Auth-Type' 
			and radcheck.value='reject' ";
        $where = $whe . " and ( clients.full_name LIKE '%$searchValue%' 
				or clients.username LIKE '%$searchValue%' 
				or clients.mobile LIKE '%$searchValue%'
			    )";
        $inner = " INNER JOIN radcheck ON clients.username=radcheck.username ";

        $sql = $API->Select("clients.*", "clients $inner", " $where ");
        $totalRecords = $sql->num_rows;
        if ($totalRecords > 0) {
            $st = $sql->fetch_all(MYSQLI_ASSOC);
            foreach ($st as $f) {
                $name = $f['username'];
                $uu = "<input type='checkbox' value='$name' name='username'>
				<a style='color:#e95100;font-size:16px;' href='/admin=user_profile/name=$name'>$name</a>";

                $data[] = array(
                    "username" => $uu,
                    "full_name" => $f['full_name'],
                    "mobile" => $f['mobile']
                );
            }
        }

    } else if ($user_type == "upcoming") {
        $time = time();
        $ext = strtotime("+10 days");
        $whe .= " and radcheck.attribute='Expiration'";

        $where = $whe . " and ( clients.full_name LIKE '%$searchValue%' 
				or clients.username LIKE '%$searchValue%' 
				or clients.mobile LIKE '%$searchValue%'
			    )";
        $inner = " INNER JOIN radcheck ON clients.username=radcheck.username ";

        $sql = $API->Select("clients.*, radcheck.value", "clients $inner", "$where ");

        $totalRecords = $sql->num_rows;
        if ($totalRecords > 0) {
            $st = $sql->fetch_all(MYSQLI_ASSOC);

            foreach ($st as $f) {
                $val = strtotime($f['value']);
                if ($val <= $ext and $val >= $time) {
                    $name = $f['username'];
                    $uu = "<input type='checkbox' value='$name' name='username'>
				<a style='color:#e95100;font-size:16px;' href='/admin=user_profile/name=$name'>$name</a>";

                    $data[] = array(
                        "username" => $uu,
                        "full_name" => $f['full_name'],
                        "mobile" => $f['mobile']
                    );
                }
            }
        }

    } else if ($user_type == "expire") {
        $time = time();
        $ext = strtotime("+10 days");
        $whe .= " and radcheck.attribute='Expiration'";

        $where = $whe . " and ( clients.full_name LIKE '%$searchValue%' 
				or clients.username LIKE '%$searchValue%' 
				or clients.mobile LIKE '%$searchValue%'
			    )";
        $inner = " INNER JOIN radcheck ON clients.username=radcheck.username ";

        $sql = $API->Select("clients.*, radcheck.value", "clients $inner", " $where ");

        $totalRecords = $sql->num_rows;
        if ($totalRecords > 0) {
            $st = $sql->fetch_all(MYSQLI_ASSOC);

            foreach ($st as $f) {
                $val = strtotime($f['value']);
                if ($val <= $time) {
                    $name = $f['username'];
                    $uu = "<input type='checkbox' value='$name' name='username'>
				<a style='color:#e95100;font-size:16px;' href='/admin=user_profile/name=$name'>$name</a>";

                    $data[] = array(
                        "username" => $uu,
                        "full_name" => $f['full_name'],
                        "mobile" => $f['mobile']
                    );
                }
            }
        }

    } else if ($user_type == "previous") {
        $time = time();
        $ext = strtotime("-30 days");
        $whe .= " and radcheck.attribute='Expiration'";

        $where = $whe . " and ( clients.full_name LIKE '%$searchValue%' 
				or clients.username LIKE '%$searchValue%' 
				or clients.mobile LIKE '%$searchValue%'
			    )";
        $inner = " INNER JOIN radcheck ON clients.username=radcheck.username ";

        $sql = $API->Select("clients.*, radcheck.value", "clients $inner", "$where");

        $totalRecords = $sql->num_rows;
        if ($totalRecords > 0) {
            $st = $sql->fetch_all(MYSQLI_ASSOC);

            foreach ($st as $f) {
                $val = strtotime($f['value']);
                if ($val <= $ext) {
                    $name = $f['username'];
                    $uu = "<input type='checkbox' value='$name' name='username'>
				<a style='color:#e95100;font-size:16px;' href='/admin=user_profile/name=$name'>$name</a>";

                    $data[] = array(
                        "username" => $uu,
                        "full_name" => $f['full_name'],
                        "mobile" => $f['mobile']
                    );
                }
            }
        }

    } else {
        $time = time();
        $whe .= " and radcheck.attribute='Expiration'";

        $where = $whe . " and ( clients.full_name LIKE '%$searchValue%' 
				or clients.username LIKE '%$searchValue%' 
				or clients.mobile LIKE '%$searchValue%'
			    )";
        $inner = " INNER JOIN radcheck ON clients.username=radcheck.username ";

        $sql = $API->Select("clients.*, radcheck.value", "clients $inner", "$where");

        $totalRecords = $sql->num_rows;
        if ($totalRecords > 0) {
            $st = $sql->fetch_all(MYSQLI_ASSOC);
            foreach ($st as $f) {
                $val = strtotime($f['value']);
                if ($val >= $time) {
                    $name = $f['username'];
                    $uu = "<input type='checkbox' value='$name' name='username'>
				<a style='color:#e95100;font-size:16px;' href='/admin=user_profile/name=$name'>$name</a>";

                    $data[] = array(
                        "username" => $uu,
                        "full_name" => $f['full_name'],
                        "mobile" => $f['mobile']
                    );
                }
            }
        }

    }

## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $row,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => $data
    );

    echo json_encode($response);

}
