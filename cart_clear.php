<?php
session_start();
include('top/db_connect.php');

// Проверяем авторизацию
if(!isset($_SESSION['my_inside'])){
    header('Location: login.php');
    exit();
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Удаляем все товары из корзины пользователя
mysqli_query($db, "DELETE FROM cart WHERE user_id = $user_id");

// Возвращаемся на страницу корзины
header('Location: cart.php');
exit();
?>