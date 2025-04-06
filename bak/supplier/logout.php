<?php
if (!isset($_SESSION['username'])) {
    header("location:../login.php");
}
session_start();

if (session_destroy()) {
    header("Location:../login.php");
}
?>