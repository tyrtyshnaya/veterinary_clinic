<?php
session_start();

if(isset($_SESSION['history'])){
    unset($_SESSION['history']);
}

header('Location: histori.php');
exit;
?>