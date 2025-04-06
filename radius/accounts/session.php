<?php
include('../config/data.php');
session_start();

if ($_SESSION['username'] == null || $_SESSION['dept'] != "Accounts" || (!isset($_SESSION['username']))) {
    header("location:../login.php");
}
?>
