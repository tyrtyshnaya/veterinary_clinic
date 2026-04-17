<?php
session_start();
include '../top/db_connect.php';

// Проверка авторизации админа
if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Получаем фильтр статуса
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Формируем запрос в зависимости от фильтра
if ($status_filter == 'all') {
    $sql = "SELECT o.*, u.login FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.date DESC";
} else {
    $sql = "SELECT o.*, u.login FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.status = '$status_filter' 
            ORDER BY o.date DESC";
}

$orders = mysqli_query($db, $sql);

include '../adm/top_adm/header.php';
?>

<div class="content">
    <h2 class="section-title">Управление заказами</h2>
    
    <!-- Фильтр по статусам -->
    <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="?status=all" class="filter-btn <?php echo $status_filter == 'all' ? 'active' : ''; ?>">Все заказы</a>
        <a href="?status=Новый" class="filter-btn <?php echo $status_filter == 'Новый' ? 'active' : ''; ?>">Новые</a>
        <a href="?status=Подтвержден" class="filter-btn <?php echo $status_filter == 'Подтвержден' ? 'active' : ''; ?>">Подтвержденные</a>
        <a href="?status=Отменен" class="filter-btn <?php echo $status_filter == 'Отменен' ? 'active' : ''; ?>">Отмененные</a>
    </div>
    
    <!-- Таблица заказов -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Дата</th>
                    <th>Покупатель</th>
                    <th>Телефон</th>
                    <th>Сумма</th>
                    <th>Кол-во товаров</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($orders) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($orders)): 
                        // Считаем количество товаров в заказе
                        $items_count_result = mysqli_query($db, "SELECT SUM(quantity) as total FROM order_items WHERE order_number = '{$order['order_number']}'");
                        $items_count_data = mysqli_fetch_assoc($items_count_result);
                        $items_count = $items_count_data['total'];
                    ?>
                        <tr>
                            <td><strong><?php echo $order['order_number']; ?></strong></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['fullname'] ?: $order['login']); ?></td>
                            <td><?php echo htmlspecialchars($order['phone'] ?: '-'); ?></td>
                            <td><?php echo number_format($order['total_amount'], 0, '', ' '); ?> ₽</td>
                            <td><?php echo $items_count; ?> шт.</td>
                            <td>
                                <span class="status-<?php echo $order['status']; ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="admin_order_view.php?id=<?php echo $order['id']; ?>" class="btn-action">Просмотр</a>
                                <?php if ($order['status'] == 'Новый'): ?>
                                    <a href="admin_order_confirm.php?id=<?php echo $order['id']; ?>
                                    " class="btn-action btn-confirm" onclick="return confirm('Подтвердить заказ?')">Подтвердить</a>
                                    <a href="admin_order_cancel.php?id=<?php echo $order['id']; ?>
                                    " class="btn-action btn-cancel" onclick="return confirm('Отменить заказ? Укажите причину.')">Отменить</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Заказов не найдено</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.filter-btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
    border: 1px solid #ddd;
}
.filter-btn.active {
    background-color: #4a6fa5;
    color: white;
    border-color: #4a6fa5;
}
.btn-confirm {
    background-color: #27ae60;
}
.btn-cancel {
    background-color: #e67e22;
}
.status-Новый {
    color: #e74c3c;
    background-color: #fdeaea;
    padding: 4px 10px;
    display: inline-block;
}
.status-Подтвержден {
    color: #27ae60;
    background-color: #e9f7ef;
    padding: 4px 10px;
    display: inline-block;
}
.status-Отменен {
    color: #95a5a6;
    background-color: #ecf0f1;
    padding: 4px 10px;
    display: inline-block;
}
</style>

<?php include '../adm/top_adm/footer.php'; ?>