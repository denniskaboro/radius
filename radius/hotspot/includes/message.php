<?php
if (isset($result)) {
    $st = str_replace("'", "", $result['status']);
    $message = str_replace("'", "", $result['message']);
    if ($st == "success") {
        echo "<script>
            Lobibox.notify('success', {
		pauseDelayOnHover: true,
		size: 'mini',
		icon: 'bx bx-check-circle',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: '{$st} : {$message}'
	});
    </script>";
    } else {
        echo "<script>
         Lobibox.notify('error', {
		pauseDelayOnHover: true,
		size: 'mini',
		icon: 'bx bx-x-circle',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: '$st:$message'
	});
    </script>";

    }
} ?>
<!--notification js -->

