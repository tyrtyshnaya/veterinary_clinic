<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['my_inside'])) {
    header('Location: callback.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($phone)) {
    header('Location: callback.php?error=Заполните имя и телефон');
    exit;
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_sql = "SELECT id FROM users WHERE login = '$login' LIMIT 1";
$user_result = dbquery($user_sql);
$user = dbfetcha($user_result);

if (!$user) {
    header('Location: callback.php?error=Ошибка пользователя');
    exit;
}

// Сохраняем заявку
$sql = "INSERT INTO callback_requests (user_id, name, phone, message, status) 
        VALUES ('{$user['id']}', '$name', '$phone', '$message', 'новый')";
        
if (dbquery($sql)) {
    header('Location: callback.php?success=1');
} else {
    header('Location: callback.php?error=Ошибка сохранения');
}
exit;
?>