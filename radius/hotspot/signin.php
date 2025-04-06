<?php
include "includes/header.php";
?>
<div class="row d-flex align-items-center justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-md-6">
                        <img src="/site/logo.png" width="200px" height="100px">
                    </div>
                </div>
                <h5 class="text-facebook text-center">Netcom Internet Hotspot</h5>
            </div>
            <div class="card-body">
                <div class="border p-2 rounded">
                    <div class="text-center">
                        <h5 class="text-primary">
                            How to Purchase:
                        </h5>
                        <div style="text-align: left !important; color: #6f42c1; font-size: 15px !important;">
                            <li style="list-style:none;">1.Tap on your preferred package.</li>
                            <li style="list-style:none;">2. Enter your phone number</li>
                            <li style="list-style:none;">3. Click "PAY NOW", wait for M-Pesa prompt.</li>
                            <li style="list-style:none;">4. Enter your M-Pesa pin, wait for 30sec for M-Pesa authentication.</li>
                            <li style="list-style:none;">5. Wait for " The request is processed successfully " Then press OK.
                                ( Reach us through 0743217821/0701250798
                            </li>
                        </div>
                        <p class="text-primary">If you have voucher code.
                            You can connect here
                        </p>
                        <a class="btn btn-danger" href="/hotspot/manual/">Connect</a>
                        <hr>
                        <h6 class="text-dark">CHOOSE SUITABLE WIFI PLAN</h6>
                        <hr>
                        <?php
                        $sql = "SELECT * FROM groups WHERE dashboard=1";
                        $r = mysqli_query($con, $sql);
                        while ($f = mysqli_fetch_array($r)) {
                            $price = $f['price'];
                            $name = $f['groupname'];
                            $id = $f['id'];
                            ?>
                            <div class=" d-flex justify-content-center">
                                <button data-bs-toggle="modal"
                                        onclick="setId('<?php echo $id; ?>')"
                                        data-bs-target="#payment"
                                        class="btn btn-radius m-1"
                                        style="width: 250px !important; height: 30px !important;font-size: 14px !important;padding: 10px !important;"><?php echo $name . "-" . $price . "Ksh"; ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
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
