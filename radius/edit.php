<?php
include 'includes/header.php';
if (isset($_GET['val'])) {
    $query = $_GET['val'];
    $id = $_GET['id'];
    $sql = "SELECT * FROM $query WHERE id='$id'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);


}
if ($query == "radcheck") {
    $sql = "SELECT * FROM $query WHERE attribute='Cleartext-Password' && username='$id'";
    $pass = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $passw = mysqli_fetch_array($pass);
    ?>
    <div class="col-sm-4">
        <h2>Update User Info:</h2>
        <form method="POST" data-toggle="validator" action="user_list.php" role="form">
            <br class="form-group col-sm-8 ">
            <label class="control-label" for="inputProfile_Name">User Name:</label>
            <input type="text" class="form-control" id="inputProfile_Name" value="<?php
            echo $id; ?>" name="username" required>
            <input type="hidden" value="<?php echo $id; ?>" name="old_name"/>

            <label class="control-label" for="inputPassword">Password:</label>
            <input type="text" class="form-control" id="inputPassword" value="<?php echo $passw['value']; ?>"
                   name="password" required>
            <input type="hidden" value="<?php echo $passw['value']; ?>" name="old_pass"/>

            <label class="control-label" for="inputProfile">Existing Package:</label>
            <!--        showing profile-->
            <input type="text" class="form-control" id="inputProfile" readonly name="pack" value="<?php
            $sqlp = "SELECT * FROM radusergroup WHERE username='$id'";
            $p = mysqli_query($con, $sqlp) or $msg = mysqli_error($con);
            $res = mysqli_fetch_array($p);
            echo $res['groupname']; ?>">
            <input type="hidden" class="form-control" value="<?php echo $res['groupname']; ?>"
                   name="old_pack">
            <label class="control-label">Set New Package:</label>
            <select class="form-control" onchange="showPro(this.value)">
                <option></option>
                <option>None</option>
                <?php
                $sql = "SELECT * FROM groups ";
                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                while ($sim = mysqli_fetch_array($r)) {
                    ?>
                    <option><?php echo $sim['groupname']; ?></option>
                <?php } ?>
            </select>
            <label class="control-label">Supplier Name:</label>
            <input type="text" class="form-control" id="inputSupplier" readonly name="supplier" value="<?php
            $sqlp = "SELECT * FROM supplier WHERE supplier_id='$id'";
            $p = mysqli_query($con, $sqlp) or $msg = mysqli_error($con);
            $res = mysqli_fetch_array($p);
            echo $res['full_name'];
            ?>">
            <input type="hidden" class="form-control" value="<?php echo $res['supplier_id']; ?>"
                   name="old_sup">
            <label class="control-label">New Supplier Name:</label>
            <select class="form-control" name="new_sup">
                <option></option>
                <option>None</option>
                <?php
                $sql = "SELECT * FROM supplier ";
                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                while ($sim = mysqli_fetch_array($r)) {
                    ?>
                    <option value="<?php echo $sim['supplier_id']; ?>"><?php echo $sim['full_name']; ?></option>
                <?php } ?>
            </select><br>
            <button type="submit" name="user" class="btn btn-info">Update</button>
    </div>
    </form>
    <script>
        function showPro(str) {
            if (str == "") {
                document.getElementById("inputProfile").innerHTML = "";
                return;
            } else {
                document.getElementById("inputProfile").value = str;
            }

        }
    </script>
    </div>
<?php }
if ($query == "nas") {
    $sql = "SELECT * FROM $query WHERE nasname='$id'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    ?>
    <div class="col-sm-5">
        <h2>Update <?php echo $query; ?></h2>
        <br>
        <form method="POST" data-toggle="validator" action="nas_list.php?id=<?php echo $id; ?>" role="form">
            <div class="form-group col-sm-8 ">

                <label for="inputIP">IP address:</label>
                <input type="text" class="form-control" id="inputIP" value="<?php echo $f['nasname']; ?>" name="nasip"
                       required>


                <label for="inputPassword">Radius Secret:</label>
                <input type="text" id="inputPassword" class="form-control" value="<?php echo $f['secret']; ?>"
                       name="secret" required>

                <label for="inputPassword">Login User:</label>
                <input type="text" id="inputPassword" class="form-control" value="<?php echo $f['login_user']; ?>"
                       name="login_user" required>
                <label for="inputPassword">Login Password:</label>
                <input type="text" id="inputPassword" class="form-control" value="<?php echo $f['login_password']; ?>"
                       name="login_password" required>
                <label for="inputPassword">API Port:</label>
                <input type="text" id="inputPassword" class="form-control" value="<?php echo $f['login_port']; ?>"
                       name="api_port" required>


                <label for="inputNAS">Location:</label>
                <input type="text" class="form-control" id="inputNAS" value="<?php echo $f['location']; ?>" name="des">


                <label for="inputNasname">NAS Name:</label>
                <input type="text" id="inputNasname" class="form-control" value="<?php echo $f['shortname']; ?>"
                       name="nasname">

                <label for="inputType">NAS Type:</label>
                <select required id="inputType" class="form-control" name="nastype">
                    <option></option>
                    <option>other</option>
                </select><br>

                <button type="submit" name="nas" class="btn btn-info">UPDATE</button>
            </div>
        </form>
    </div>

<?php }
if ($query == "groups") { ?>
    <div class="col-sm-5">
        <h2>Update Package Name</h2>
        <form method="POST" data-toggle="validator" action="group_list.php" role="form">
            <div class="form-group col-sm-8 ">
                <label class="control-label" for="inputGroup">Group Name:</label>
                <input type="text" class="form-control" id="inputGroup" name="group"
                       value="<?php echo $f['groupname']; ?>">
                <input type="hidden" value="<?php echo $f['id']; ?>" name="gid"/><br>
            </div>
            <div class="form-group col-sm-8 ">
                <label for="inputFullname" class="control-label">Price(Tk.)</label>
                <input type="text" class="form-control" id="inputFullname" value="<?php echo $f['price']; ?>"
                       name="price">
            </div>
            <div class="form-group col-sm-8 ">
                <label for="inputAddress" class="control-label">Duration</label>
                <p>(valid format= 1 days, 1 Month)</p>
                <input type="text" class="form-control" value="<?php echo $f['duration']; ?>"
                       name="duration">
            </div>
            <div class="form-group col-sm-8 ">
                <label for="inputAddress" class="control-label">Download Limit</label>
                <p>(valid format= 1 GB, 1 MB)</p>
                <input type="text" class="form-control" value="<?php echo $f['data']; ?>"
                       name="data">
            </div>

            <div class="form-group col-sm-8 ">
                <label for="inputAddress" class="control-label">Speed</label>
                <p>(valid format= 5M, 512K)</p>
                <input type="text" class="form-control" value="<?php echo $f['speed']; ?>"
                       name="speed">
                <br>
                <button type="submit" name="grp" class="btn btn-info">Update</button>
            </div>

        </form>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
