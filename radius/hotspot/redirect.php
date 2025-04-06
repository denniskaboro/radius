<?php
function protect($string)
{
    $protection = htmlspecialchars(trim($string), ENT_QUOTES);
    return $protection;
}

function isValidURL($url)
{
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

$a = $_GET['url'];
$url = protect($a);
if ($url) {
    include "$url.php";
} else {
    session_destroy();
    include "index.php";
}
?>
