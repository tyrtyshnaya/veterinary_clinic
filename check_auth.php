<?php
session_start();

if(!isset($_SESSION['my_inside']) || $_SESSION['my_inside'] != 1){
    header("Location: login.php");
    exit;
}

?>