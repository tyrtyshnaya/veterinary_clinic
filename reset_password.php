<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit;
}

$login = trim($_POST['login'] ?? '');
$email = trim($_POST['email'] ?? '');

if (empty($login) || empty($email)) {
    header('Location: forgot_password.php?error=Заполните все поля');
    exit;
}

// Проверяем существование пользователя
$sql = "SELECT * FROM users WHERE login = '$login'";
$result = dbquery($sql);
$user = dbfetcha($result);

if (!$user) {
    header('Location: forgot_password.php?error=Пользователь не найден');
    exit;
}

// Проверяем email (если есть поле email в таблице)
if (isset($user['email']) && $user['email'] != $email) {
    header('Location: forgot_password.php?error=Email не совпадает');
    exit;
}

// Генерируем временный пароль (8 символов)
$temp_password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
$hashed_temp = md5($temp_password); // хэшируем временный пароль

// Сохраняем временный пароль в БД
$update_sql = "UPDATE users SET temp_password='$hashed_temp' WHERE login='$login'";
dbquery($update_sql);

// Перенаправляем с временным паролем на экран
header("Location: forgot_password.php?success=" . urlencode($temp_password));
exit;
?>