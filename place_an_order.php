<?php
session_start();
include 'top/db_connect.php';
include 'top/header.php';

// Проверяем авторизацию
if (!isset($_SESSION['my_inside'])) {
    header("Location: login.php");
    exit();
}

// Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit();
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_query = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_query);
$user_id = $user_data['id'];

// Получаем данные из формы
$selected_items = isset($_POST['items']) ? $_POST['items'] : [];
$fullname = mysqli_real_escape_string($db, $_POST['fullname'] ?? '');
$phone = mysqli_real_escape_string($db, $_POST['phone'] ?? '');
$email = mysqli_real_escape_string($db, $_POST['email'] ?? '');
$address = mysqli_real_escape_string($db, $_POST['address'] ?? '');
$comment = mysqli_real_escape_string($db, $_POST['comment'] ?? '');
$total_amount = (int)$_POST['total_amount'];

// Проверяем, что выбран хотя бы один товар
if (empty($selected_items)) {
    echo '<div style="max-width: 600px; margin: 60px auto; text-align: center;">
            <h2>Ошибка!</h2>
            <p>Вы не выбрали ни одного товара для заказа.</p>
            <a href="making_an_order.php" class="btn-checkout" style="display: inline-block; background: #4CAF50; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none;">Вернуться к оформлению</a>
          </div>';
    include 'top/footer.php';
    exit();
}

// Генерируем номер заказа
$order_number = 'ЗАКАЗ №' . date('Ymd') . '-' . rand(100, 999);

// Сохраняем заказ в таблицу orders
$sql = "INSERT INTO orders (order_number, user_id, date, fullname, phone, email, address, comment, total_amount) 
        VALUES ('$order_number', $user_id, NOW(), '$fullname', '$phone', '$email', '$address', '$comment', $total_amount)";
mysqli_query($db, $sql);

// Сохраняем только выбранные товары в order_items
foreach ($selected_items as $cart_id) {
    $cart_id = (int)$cart_id;
    
    // Получаем данные товара из корзины
    $item_query = mysqli_query($db, "SELECT c.*, s.title, s.price 
            FROM cart c 
            JOIN shop s ON c.product_id = s.id 
            WHERE c.id = $cart_id AND c.user_id = $user_id");
    $item = mysqli_fetch_assoc($item_query);
    
    if ($item) {
        // Очищаем цену от пробелов и символов
        $clean_price = preg_replace('/[^0-9]/', '', $item['price']);
        $price = (int)$clean_price;
        $quantity = (int)$item['quantity'];
        
        $title = mysqli_real_escape_string($db, $item['title']);
        
        // Исправленный запрос - без лишних пробелов
        $insert_sql = "INSERT INTO order_items (order_number, product_title, product_price, quantity) 
                       VALUES ('$order_number', '$title', $price, $quantity)";
        
        if (!mysqli_query($db, $insert_sql)) {
            echo "Ошибка: " . mysqli_error($db);
            exit();
        }
        
        // Уменьшаем остаток на складе
        mysqli_query($db, "UPDATE shop SET stock = stock - $quantity WHERE id = {$item['product_id']}");
        
        // Удаляем товар из корзины
        mysqli_query($db, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    }
}

// Обновляем данные пользователя, если они изменились
mysqli_query($db, "UPDATE users SET fullname='$fullname', phone='$phone', email='$email' WHERE id=$user_id");
?>

<div style="max-width: 600px; margin: 60px auto; text-align: center;">
    <div style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #333; margin-bottom: 10px;">Заказ оформлен!</h2>
        <p style="color: #666; margin-bottom: 15px;">Спасибо за покупку</p>
        
        <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <span style="color: #666;">Номер заказа:</span>
            <strong style="color: #4CAF50; font-size: 20px; display: block; margin-top: 5px;"><?php echo $order_number; ?></strong>
        </div>
        
        <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0; color: #4CAF50;">Вы сможете забрать заказ по адресу:</p>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Московская ул., 104, Орёл</p>
        </div>
        
        <a href="shop.php" style="display: inline-block; background: #4CAF50; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none;">
            Вернуться в каталог
        </a>
        <a href="order_history.php" style="display: inline-block; background: #2196F3; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; margin-left: 10px;">
            Мои заказы
        </a>
    </div>
</div>

<?php
include 'top/footer.php';
?>