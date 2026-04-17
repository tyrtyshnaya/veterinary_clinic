<?php
session_start();
include '../top/db_connect.php';

if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit;
}

$id = (int)$_GET['id'];

// Получаем фото товара, чтобы удалить файл
$result = mysqli_query($db, "SELECT img FROM shop WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if ($product && !empty($product['img'])) {
    $file_path = '../images/' . $product['img'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Удаляем товар
mysqli_query($db, "DELETE FROM shop WHERE id = $id");

header('Location: admin_shop.php');
exit;
?>