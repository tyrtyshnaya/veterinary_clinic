<?php
session_start();
include('top/db_connect.php');

header('Content-Type: application/json');

// Включаем отладку (можно убрать потом)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['my_inside'])) {
    echo json_encode([]);
    exit();
}

$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");

if (!$user_result || mysqli_num_rows($user_result) == 0) {
    echo json_encode([]);
    exit();
}

$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

$sql = "SELECT product_id FROM favorites WHERE user_id = $user_id";
$result = mysqli_query($db, $sql);

$favorites = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $favorites[] = (int)$row['product_id'];
    }
}

// Отладочная информация (будет видна в консоли браузера)
error_log("User ID: $user_id, Favorites: " . implode(',', $favorites));

echo json_encode($favorites);
exit();
?>