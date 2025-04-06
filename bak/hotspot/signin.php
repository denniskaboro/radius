<?php
include "includes/header.php";
?>
<div class="section-authentication-signin d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
                <div class="card mt-5 mt-lg-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="/site/logo.png" width="200px" height="100px">
                        </div>
                        <div class="card-header">
                            <h5 class="text-facebook">Welcome to NETCOMINTERNET</h5>
                        </div>
                        <div class="border p-4 rounded">
                            <div class="text-center">
                                <?php
                                $sql = "SELECT * FROM groups WHERE dashboard=1";
                                $r = mysqli_query($con, $sql);
                                while ($f = mysqli_fetch_array($r)) {
                                    $name = $f['groupname'];
                                    $id = $f['id'];
                                    ?>
                                    <div class="d-flex justify-content-center">
                                        <button data-bs-toggle="modal" onclick="setId('<?php echo $id; ?>')"
                                                data-bs-target="#payment"
                                                class="btn btn-radius m-1"
                                                style="font-size: 16px !important;padding: 10px !important;"><?php echo $name; ?>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<style>
    .lds-roller {
        left: 40% !important;
    }
</style>
<div class="col">
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-radius">Please complete your payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputSet">Enter Number e.g 0700******: </label>
                        <p>(Number format: 7)</p>
                        <input type="text" id="number" name="number" class="form-control" required>
                        <input type="hidden" id="group_id" name="group_id" class="form-control" required>
                    </div>
                    <div id="result">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-sm btn-danger" onclick="Payment()">Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function setId(gId) {
        document.getElementById("group_id").value = gId;
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function Payment() {
        document.getElementById("result").innerHTML = '<div class="lds-roller">' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '</div>';
        var number = document.getElementById('number').value;
        var group_id = document.getElementById('group_id').value;
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                if (data.status == "success") {
                    console.log(data);
                    alert(data.message + ". Don't close the browser. We are checking your payment");
                    var CheckoutRequestID = data.CheckoutRequestID;
                    setInterval(function () {
                        loadlink(CheckoutRequestID) // this will run after every 12 seconds
                    }, 12000);
                } else {
                    alert(data.message);
                    $("#payment").modal("hide");
                }
            }
        }
        xmlhttp.open("GET", "/hotspot/LipaNaMpesa.php?number=" + number + "&group_id=" + group_id, true);
        xmlhttp.send();
    }

    function loadlink(CheckoutRequestID) {
        $.ajax({
            url: "/hotspot/verify.php",
            type: 'POST',
            data: {"CheckoutRequestID": CheckoutRequestID},
            success: function (response) {
                var result = JSON.parse(response);
                console.log(result);
                if (result.status == "success") {
                    alert(result.message);
                    window.location.assign("/hotspot/transfer/");
                } else {
                    alert(result.message);
                }
            }
        });
    }


</script>

<?php
include "includes/footer.php";
?>
