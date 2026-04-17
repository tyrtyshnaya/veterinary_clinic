<?php
session_start();
include '../top/db_connect.php';

if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit();
}

$id = (int)$_GET['id'];

// Обновляем статус заказа
mysqli_query($db, "UPDATE orders SET status = 'Подтвержден' WHERE id = $id");

header('Location: admin_order_view.php?id=' . $id);
exit();
?>