<?php
session_start();
include('top/db_connect.php');

$cart_id = $_POST['cart_id'];
mysqli_query($db, "DELETE FROM cart WHERE id = $cart_id");
?>