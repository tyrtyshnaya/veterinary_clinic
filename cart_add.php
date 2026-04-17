<?php
session_start();
include('top/db_connect.php');

// Включаем вывод ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ИСПРАВЛЕНО: проверяем правильную переменную сессии
if(!isset($_SESSION['my_inside'])){
    echo "ОШИБКА: пользователь не авторизован";
    exit();
}

// ИСПРАВЛЕНО: получаем user_id из БД по логину
$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Проверяем, пришли ли данные
if(!isset($_POST['product_id'])){
    echo "ОШИБКА: не передан product_id";
    exit();
}

$product_id = (int)$_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;   //получаем новое колличество товара 

// Проверяем, есть ли товар в корзине
$check_sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$check_result = mysqli_query($db, $check_sql);

if(mysqli_num_rows($check_result) > 0){
    // Обновляем количество
    $update_sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($db, $update_sql);
    echo "Товар обновлён в корзине";
} else {
    // Добавляем новый товар
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
    mysqli_query($db, $insert_sql);
    echo "Товар добавлен в корзину";
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>