<?php
session_start();
echo $_POST['link-login-only'];
$linkloginonly=$_POST['link-login-only'];
?>
<div id="wrap">
    <div class="container">
        <div class="col-md-6 col-sm-12">        
            <div class="row">
                <div class="alert alert-success">
                    You are logged in successfully. If nothing happens, click <a href="<?php echo $linkloginonly; ?>">here</a>.
                </div>
            </div>
        </div>
    </div> 
</div>

<script>
    window.location.assign('<?php echo $linkloginonly; ?>/status');
</script>
