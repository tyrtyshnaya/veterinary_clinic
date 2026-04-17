<?php
session_start();
include('top/db_connect.php');

// Проверяем авторизацию
if (!isset($_SESSION['my_inside'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Получаем ID товара
$product_id = (int)$_POST['product_id'];

// Удаляем из избранного
$delete_sql = "DELETE FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
mysqli_query($db, $delete_sql);

echo json_encode(['status' => 'removed']);
?>