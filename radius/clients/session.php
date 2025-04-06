<?php
session_start();
if ($_SESSION['username'] == null || (!isset($_SESSION['username']))) {
    session_destroy();
    header("location:../login.php");
}
if ($_SESSION['pass'] == null || (!isset($_SESSION['pass']))) {
    session_destroy();
    header("location:../login.php");
}

?>
