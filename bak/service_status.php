<?php include 'includes/header.php'; ?>
<?php

function check_service($sname)
{
    if ($sname != '') {
        system("pgrep " . escapeshellarg($sname) . " >/dev/null 2>&1", $ret_service);
        if ($ret_service == 0) {
            return "Enabled";
        } else {
            return "Disabled";
        }
    } else {
        return "no service name";
    }
}

?>
<div class="col-md-6">
    <?php
    echo "<h3>Service Status</h3>";
    ?>

    <table class='summarySection table'>
        <tr>
            <td class='summaryKey'> Radius</td>
            <td class='summaryValue'><span class='sleft'><?php echo check_service("radius"); ?></span></td>
        </tr>
        <tr>
            <td class='summaryKey'> Mysql</td>
            <td class='summaryValue'><span class='sleft'><?php echo check_service("mysql"); ?></span></td>
        </tr>
    </table>
</div>

</div>  <!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
