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

$cart_id = (int)$_POST['cart_id'];
$quantity = (int)$_POST['quantity'];

if ($quantity <= 0) {
    // Удаляем товар
    mysqli_query($db, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
} else {
    // Обновляем количество
    mysqli_query($db, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
}

// Получаем обновленные данные корзины
$items_query = mysqli_query($db, "SELECT c.*, s.price, s.title 
    FROM cart c 
    JOIN shop s ON c.product_id = s.id 
    WHERE c.user_id = $user_id");

$cart_items = [];
$total = 0;
$items_count = 0;

while ($row = mysqli_fetch_assoc($items_query)) {
    $clean_price = preg_replace('/[^0-9]/', '', $row['price']);
    $row['price'] = (int)$clean_price;
    
    $cart_items[] = [
        'id' => $row['id'],
        'product_id' => $row['product_id'],
        'title' => $row['title'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'item_total' => $row['price'] * $row['quantity']
    ];
    $total += $row['price'] * $row['quantity'];
    $items_count += $row['quantity'];
}

// Рассчитываем скидку
$discount = 0;
if ($total >= 5000) {
    $discount = $total * 0.05;
}
$final_total = $total - $discount;

echo json_encode([
    'success' => true,
    'items' => $cart_items,
    'total' => $total,
    'items_count' => $items_count,
    'discount' => $discount,
    'final_total' => $final_total
]);
?>