<?php
session_start();
include '../top/db_connect.php';

if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit();
}

$id = (int)$_GET['id'];

// Получаем данные заказа
$order_result = mysqli_query($db, "SELECT o.*, u.login FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        WHERE o.id = $id");
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    header('Location: admin_orders.php');
    exit();
}

// Получаем товары в заказе
$items_result = mysqli_query($db, "SELECT * FROM order_items WHERE order_number = '{$order['order_number']}'");

include '../adm/top_adm/header.php';
?>

<div class="content">
    <h2 class="section-title">Заказ <?php echo $order['order_number']; ?></h2>
    
    <a href="admin_orders.php" class="btn-action" style="margin-bottom: 20px; display: inline-block;">← Назад к заказам</a>
    
    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        <!-- Информация о заказе -->
        <div style="flex: 1; background: white; padding: 20px; border: 1px solid #ddd;">
            <h3>Информация о заказе</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 8px;"><strong>Номер:</strong></td><td><?php echo $order['order_number']; ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Дата:</strong></td><td><?php echo date('d.m.Y H:i', strtotime($order['date'])); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Статус:</strong></td><td><span class="status-<?php echo $order['status']; ?>">
                    <?php echo $order['status']; ?></span></td></tr>
                <tr><td style="padding: 8px;"><strong>Сумма:</strong></td><td><?php echo number_format($order['total_amount'], 0, '', ' '); ?> ₽</td></tr>
                <?php if ($order['cancel_reason']): ?>
                <tr><td style="padding: 8px;"><strong>Причина отмены:</strong></td><td style="color: #e74c3c;">
                    <?php echo htmlspecialchars($order['cancel_reason']); ?></td></tr>
                <?php endif; ?>
            </table>
        </div>
        
        <!-- Данные покупателя -->
        <div style="flex: 1; background: white; padding: 20px; border: 1px solid #ddd;">
            <h3>Данные покупателя</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 8px;"><strong>Логин:</strong></td><td><?php echo htmlspecialchars($order['login']); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>ФИО:</strong></td><td><?php echo htmlspecialchars($order['fullname'] ?: '-'); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Телефон:</strong></td><td><?php echo htmlspecialchars($order['phone'] ?: '-'); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Email:</strong></td><td><?php echo htmlspecialchars($order['email'] ?: '-'); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Адрес:</strong></td><td><?php echo htmlspecialchars($order['address'] ?: '-'); ?></td></tr>
                <tr><td style="padding: 8px;"><strong>Комментарий:</strong></td><td><?php echo nl2br(htmlspecialchars($order['comment'] ?: '-')); ?></td></tr>
            </table>
        </div>
    </div>
    
    <!-- Товары в заказе -->
    <div style="margin-top: 30px; background: white; padding: 20px; border: 1px solid #ddd;">
        <h3>Товары в заказе</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Название товара</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_title']); ?></td>
                    <td><?php echo number_format($item['product_price'], 0, '', ' '); ?> ₽</td>
                    <td><?php echo $item['quantity']; ?> шт.</td>
                    <td><?php echo number_format($item['product_price'] * $item['quantity'], 0, '', ' '); ?> ₽</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Кнопки действий -->
    <?php if ($order['status'] == 'Новый'): ?>
    <div style="margin-top: 20px; display: flex; gap: 15px;">
        <a href="admin_order_confirm.php?id=<?php echo $order['id']; ?>" 
        class="btn-action btn-confirm" style="padding: 10px 25px;" onclick="return confirm('Подтвердить заказ?')">Подтвердить заказ</a>
        <a href="admin_order_cancel.php?id=<?php echo $order['id']; ?>"
         class="btn-action btn-cancel" style="padding: 10px 25px;" onclick="return confirm('Отменить заказ?')"> Отменить заказ</a>
    </div>
    <?php endif; ?>
</div>

<style>
.status-Новый { color: #e74c3c; background-color: #fdeaea; padding: 4px 10px; display: inline-block; }
.status-Подтвержден { color: #27ae60; background-color: #e9f7ef; padding: 4px 10px; display: inline-block; }
.status-Отменен { color: #95a5a6; background-color: #ecf0f1; padding: 4px 10px; display: inline-block; }
.btn-confirm { background-color: #27ae60; }
.btn-cancel { background-color: #e67e22; }
</style>

<?php include '../adm/top_adm/footer.php'; ?>