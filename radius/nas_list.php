<?php
include 'includes/header.php';
include('config/data.php');

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
$per = $_SESSION['per'];
//For Update Query
if (isset($_POST['nas'])) {
    if ($per == "Write" || $per == "Full" || $per == "Admin") {
        $id = $_GET['id'];
        $nasip = $_POST['nasip'];
        $des = $_POST['des'];
        $secret = $_POST['secret'];
        $login_user = $_POST['login_user'];
        $login_password = $_POST['login_password'];
        $api_port = $_POST['api_port'];
        $nasname = $_POST['nasname'];
        $nastype = $_POST['nastype'];

        $sql = "UPDATE nas set nasname='$nasip', shortname='$nasname',location='$des', type='$nastype', login_user='$login_user',login_password='$login_password', secret='$secret',login_port='$api_port' WHERE nasname='$id'";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        if ($r) {
            echo "NAS Update successfully... ";
        } else {
            echo "Unsuccessfully...";
        }
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
//For Delete Attribute
if (isset($_GET['val'])) {
    if ($per == "Full" || $per == "Admin") {
        $nasip = $_GET['val'];
        $sq = "SELECT * FROM nas WHERE nasname='$nasip'  ";
        $res = mysqli_query($con, $sq);
        $f = mysqli_fetch_array($res);
        $login_name = $f['login_user'];
        $login_pass = $f['login_password'];
        if ($login_name != null) {
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($nasip, $login_name, $login_pass));
                foreach ($util->setMenu('/radius')->getAll() as $entry) {
                    $id = $entry('.id');
                }
            } catch (Exception $e) {

            }
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($nasip, $login_name, $login_pass));
                $setRequest = new RouterOS\Request(
                    '/radius remove'
                );
                echo $id;
                $setRequest
                    ->setArgument('.id', $id);
                $client->sendSync($setRequest);

            } catch (Exception $e) {
                echo "Unable to connect to RouterOS.";
            }
            $sq = "DELETE FROM nas WHERE nasname='$nasip'  ";
            $res = mysqli_query($con, $sq);
            echo "NAS Delete Successfully...";
        } else {
            $sq = "DELETE FROM nas WHERE nasname='$nasip'  ";
            $res = mysqli_query($con, $sq);
            echo "NAS Delete Successfully...";
        }
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
?>
<table class="table table-bordered" id="btrc">
    <h4>Network Access Server List:</h4>
    <thead>
    <tr>
        <th>Nas Name</th>
        <th>Client Name</th>
        <th>Location</th>
        <th>POP</th>
        <th>NAS IP</th>
        <th>Secret</th>
        <th>API Username</th>
        <th>API Password</th>
        <th>API Port</th>
        <th>Login Router</th>
        <?php if ($per == "Full" || $per == "Admin" || $per == "Write") { ?>
            <th>Edit</th>
        <?php } ?>
        <?php if ($per == "Full" || $per == "Admin") { ?>
            <th>Delete</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_GET['sort'])) {
        $sup_id = $_GET['sort'];
        $sql = "SELECT * FROM nas WHERE shortname='$sup_id'";
    } else {
        $sql = "SELECT * FROM nas";
    }
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {

        ?>
        <tr>
            <td><?php echo $f['shortname']; ?></td>
            <?php
            $id = $f['shortname'];
            $qu = "SELECT * FROM supplier WHERE supplier_id='$id'";
            $ff = mysqli_query($con, $qu);
            $show = mysqli_fetch_array($ff);

            ?>
            <td><?php echo $show['full_name']; ?></td>
            <td><?php echo $f['location']; ?></td>
            <td><?php echo $show['pop']; ?></td>
            <td><?php echo $f['nasname']; ?></td>
            <td><?php echo $f['secret']; ?></td>
            <td><?php echo $f['login_user']; ?></td>
            <td><?php echo $f['login_password']; ?></td>
            <td><?php echo $f['login_port']; ?></td>
            <td><a class="btn" href="system_info.php?ip=<?php echo $f['nasname']; ?>">&nbsp;
                    <span class="glyphicon glyphicon-cog"></span></a></td>

            <?php if ($per == "Full" || $per == "Admin" || $per == "Write") { ?>
                <td><a class="btn" href="edit.php?val=nas&id=<?php echo $f['nasname']; ?>"><i class="icon-edit">
                            <span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <?php } ?>
            <?php if ($per == "Full" || $per == "Admin") { ?>
                <td><a class="btn btn-danger" id="link" href="nas_list.php?val=<?php echo $f['nasname']; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script type="text/javascript">
    function myFunction() {
        var x;
        var r = confirm("Confirm to do this?");
        if (r == true) {
            document.getElementById("link").href = "nas_list.php?val=<?php echo $f['nasname']; ?>";
        } else {
            document.getElementById("link").href = "nas_list.php";
        }
    }

</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]

        });

    });
</script>
<?php include 'includes/footer.php'; ?>
