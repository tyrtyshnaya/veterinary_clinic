<?php
session_start();
include('top/db_connect.php');

header('Content-Type: application/json');

if (!isset($_SESSION['my_inside'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($quantity <= 0) {
    // Удаляем товар
    mysqli_query($db, "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    echo json_encode(['success' => true, 'quantity' => 0, 'removed' => true]);
} else {
    // Проверяем, есть ли товар в корзине
    $check = mysqli_query($db, "SELECT id FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if (mysqli_num_rows($check) > 0) {
        // Обновляем
        mysqli_query($db, "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Добавляем
        mysqli_query($db, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
    }
    echo json_encode(['success' => true, 'quantity' => $quantity]);
}
?>