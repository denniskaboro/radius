<?php
include "reseller.php";
?>
    <div class="container-fluid">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <h3>Payment</h3>
            <div class="form-group">
                <label for="inputSet">SUPPLIER ID:</label>
                <input type="text" value="<?php echo $sup_id ; ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="inputSet">Price:</label>
                <input type="text" name="amt" id="amt"  class="form-control" required>
            </div>
            <div class="form-group">
                <label for="inputSet">Enter Number e.g 0700******: </label>
                <p>(Number format: 7)</p>
                <input type="text" id="num" name="number" class="form-control" required>
            </div>
            <button name="pay" onclick="Payment()" class="btn btn-danger">
                Payment with Mpeasa
            </button>


        </div>
    </div>
    <script>
        function Payment() {
            var number = document.getElementById('num').value;
            var amt = document.getElementById('amt').value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        var data = JSON.parse(this.responseText);
                        console.log(data);
                        console.log(data.message);
                        alert(data.message);
                    }
                }
                xmlhttp.open("GET", "payment.php?number=" + number+"&amount="+amt, true);
                xmlhttp.send();
        }
    </script>
<?php include '../includes/footer.php'; ?>