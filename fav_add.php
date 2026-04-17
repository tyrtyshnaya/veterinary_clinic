<?php
session_start();
include('top/db_connect.php');

header('Content-Type: application/json');

// Проверяем авторизацию
if (!isset($_SESSION['my_inside'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
if (!$user_result || mysqli_num_rows($user_result) == 0) {
    echo json_encode(['error' => 'user_not_found']);
    exit();
}

$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

if (!isset($_POST['product_id'])) {
    echo json_encode(['error' => 'no_product_id']);
    exit();
}

$product_id = (int)$_POST['product_id'];

// Проверяем, есть ли уже в избранном
$check_sql = "SELECT id FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
$check_result = mysqli_query($db, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // Удаляем
    $delete_sql = "DELETE FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
    if (mysqli_query($db, $delete_sql)) {
        echo json_encode(['status' => 'removed']);
    } else {
        echo json_encode(['error' => 'delete_failed']);
    }
} else {
    // Добавляем
    $insert_sql = "INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)";
    if (mysqli_query($db, $insert_sql)) {
        echo json_encode(['status' => 'added']);
    } else {
        echo json_encode(['error' => 'insert_failed']);
    }
}
exit();
?>