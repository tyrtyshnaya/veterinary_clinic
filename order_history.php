<?php
session_start();
include 'top/db_connect.php';


// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_query = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_query);
$user_id = $user_data['id'];

// Получаем заказы пользователя
$orders_query = mysqli_query($db, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY date DESC");

// Только ПОСЛЕ всей логики подключаем header
include 'top/header.php';
?>

<div class="container">
    <h1 class="section-title">Мои заказы</h1>
    
    <?php if (mysqli_num_rows($orders_query) == 0): ?>
        <div class="empty-orders">
            <p>У вас пока нет заказов</p>
            <a href="shop.php" class="btn">Перейти к покупкам</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php while($order = mysqli_fetch_assoc($orders_query)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div><strong>Заказ:</strong> <?php echo $order['order_number']; ?></div>
                        <div><strong>Дата:</strong> <?php echo date('d.m.Y H:i', strtotime($order['date'])); ?></div>
                        <div class="order-status status-<?php echo $order['status'] ?? 'new'; ?>">
                            <?php echo $order['status'] ?? 'Новый'; ?>
                        </div>
                    </div>
                    <div class="order-details">
                        <div class="order-info">
                            <p><strong>Сумма:</strong> <?php echo number_format($order['total_amount'], 0, '', ' '); ?> ₽</p>
                            <p><strong>Адрес:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                            <?php if (!empty($order['comment'])): ?>
                                <p><strong>Комментарий:</strong> <?php echo htmlspecialchars($order['comment']); ?></p>
                            <?php endif; ?>
                            <?php if ($order['status'] == 'Отменен' && !empty($order['cancel_reason'])): ?>
                                <p><strong>Причина отмены:</strong> <?php echo htmlspecialchars($order['cancel_reason']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="order-items">
                            <strong>Товары:</strong>
                            <?php
                            $items_query = mysqli_query($db, "SELECT * FROM order_items WHERE order_number = '{$order['order_number']}'");
                            while($item = mysqli_fetch_assoc($items_query)):
                            ?>
                                <div class="order-item-mini">
                                    <?php echo $item['product_title']; ?> - 
                                    <?php echo $item['quantity']; ?> шт. x 
                                    <?php echo number_format($item['product_price'], 0, '', ' '); ?> ₽
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
.section-title {
    font-size: 32px;
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}
.empty-orders {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}
.btn {
    display: inline-block;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    padding: 10px 25px;
    border-radius: 5px;
}
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.order-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
}
.order-header {
    background: #f5f5f5;
    padding: 15px 20px;
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    border-bottom: 1px solid #e0e0e0;
}
.order-status {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}
.status-new, .status-Новый {
    background: #2196F3;
    color: white;
}
.status-completed, .status-Подтвержден {
    background: #4CAF50;
    color: white;
}
.status-cancelled, .status-Отменен {
    background: #f44336;
    color: white;
}
.order-details {
    padding: 20px;
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.order-info {
    flex: 1;
}
.order-info p {
    margin: 5px 0;
}
.order-items {
    flex: 2;
}
.order-item-mini {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}
</style>

<?php include 'top/footer.php'; ?>