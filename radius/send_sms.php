<?php
include 'includes/header.php';
$supplier_id = "Admin";

?>
<div class="row">
    <!-- Pie Chart -->
    <div class="col-xl-6 col-lg-6 col-md-6">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo "<h4 style='color: #2cde43'>" . $msg . "</h4>";
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">Client Details Information:</h4>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">User Category</label>
                <select class="form-control" onchange="userType()" id="user_type" name="user_type" required>
                    <option></option>
                    <option value="all">All Users</option>
                    <option value="active">Active</option>
                    <option value="deactive">Disabled</option>
                    <option value="expire">Expired</option>
                    <option value="upcoming">Upcoming Expired</option>
                    <option value="previous">Previous Expired</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Extra Mobile Number(0750,0795) Optional</label>
                <textarea class="form-control" name="mobile" id="mobile" style="height: 95%"
                          onkeyup="countMobile(this.value)"
                          cols="50" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label class="control-label">Message</label>
                <textarea class="form-control" id="message" style="height: 95%"
                          onkeyup="countMessage(this.value)"
                          name="message" cols="50" rows="4"></textarea>
            </div>
            <h5>Message Character Count:</h5><h4 id="messagelength" class="text-danger"></h4>
        </div>

    </div>

    <link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <?php
    $page = "User Results";
    $tsort = 0;

    ?>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
        <button class="btn btn-dark" style="font-size: 16px !important; ">
            <input type="checkbox" id="userCheck" onclick="MultipleAdd();" style="width: 20px; height: 20px">
            Check All
        </button>
        <button type="button" id="cus" disabled class="btn btn-success" onclick="return ManualSMSSend();"><i
                    class="bx bxs-message-square-check"></i>Send Message
        </button>
        <table id="myTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Mobile</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    function MultipleAdd() {
        $("#userCheck").change(function () {
            if (this.checked) {
                $('input[name="username"]').each(function () {
                    this.checked = true;
                });
            } else {
                $('input[name="username"]').each(function () {
                    this.checked = false;
                });
            }
        });
    }

    function userType() {
        var user_type = document.getElementById('user_type').value;
        const table_col = {
            'last': '5',
            'userType': user_type,
        };
        var table = $('#myTable').DataTable({
            'destroy': true,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': 'datatable/user_list_process.php',
                'data': table_col
            },
            'columns': [
                {data: 'username'},
                {data: 'full_name'},
                {data: 'mobile'},
            ],
            'lengthChange': true,
            <?php
            if (isset($tsort)) {
                echo "order: [[$tsort, 'desc']],";
            }
            ?>
            'lengthMenu': [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            'stateSave': true,

        });
        table.buttons().container().appendTo('#myTable_wrapper .col-md-6:eq(0)');

    }

    function countMobile(str) {
        var cc = str.length;
        if (cc > 10) {
            document.getElementById('cus').disabled = false;
        } else {
            document.getElementById('cus').disabled = true;
        }
    }

    function countMessage(str) {
        var cc = str.length;
        document.getElementById("messagelength").innerText = cc;
        if (cc > 2) {
            document.getElementById('cus').disabled = false;
        } else {
            document.getElementById('cus').disabled = true;
        }
    }

    function ManualSMSSend() {
        var r = confirm("Confirm to Send ?");
        if (r == true) {
            var arr = [];
            $.each($("input[name='username']:checked"), function () {
                arr.push($(this).val());
            });
            var new_mobile = $("#mobile").val();

            if (arr == "" && new_mobile == "") {
                alert("You must select an user.");
                return;
            }

            var checks = arr.join(",");
            console.log(checks);

            var message = $("#message").val();

            var sendata = {
                "multipleSMSSend": checks,
                "message": message,
                "ext": new_mobile
            };
            $.ajax({
                type: "POST",
                url: "/config/postdata.php",
                dataType: "json",
                data: sendata,
                success: function (response) {
                    console.log(response);
                    alert(response.message);
                },
                error(errordata) {
                    console.log(errordata);
                    var data = JSON.parse(errordata);
                    alert(data.message);
                }
            });
            document.getElementById("userCheck").checked = false;
            $.each($("input[name='username']:checked"), function () {
                this.checked = false;
            });
        }
    }
</script>

<?php include 'includes/footer.php'; ?>

