<?php
session_start();
include '../top/db_connect.php';

//проверка на авторизацию
if (!isset($_SESSION['adm_id'])){
    header('Location: admin_login.php');
    exit();
}

//список всех товаров из бд 
$result = mysqli_query($db, "SELECT s.*, c.name as category_name 
        FROM shop s 
        LEFT JOIN categories c ON s.category_id = c.id 
        ORDER BY s.id DESC");

include '../adm/top_adm/header.php';
?>

<div class="content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 class="section-title" style="margin-bottom: 0;">Управление товарами</h2>
        <a href="admin_add_product.php" class="btn-add">+ Добавить товар</a>
    </div>
    
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Остаток</th>
                    <th>Категория</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id'];?></td>
                    <td>
                        <?php if (!empty($row['img'])): ?>
                            <img src="/Vet/images/<?php echo $row['img']; ?>" style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?> 
                            <span style="color: #999;">Нет фото</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo number_format($row['price'], 0, '', ' '); ?> ₽</td>
                    <td><?php echo $row['stock']; ?> шт.</td>
                    <td><?php echo $row['category_name'] ?? 'Без категории'; ?></td>
                    <td>
                         <a href="admin_edit_product.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Редактировать</a>
                         <a href="admin_delete_product.php?id=<?php echo $row['id']; ?>
                         " class="btn-action btn-delete" onclick="return confirm('Удалить товар?')">Удалить</a>
                     </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
         </table>
    </div>
</div>

<style>
.btn-add {
    display: inline-block;
    background-color: #27ae60;
    color: white;
    padding: 8px 18px;
    text-decoration: none;
    font-size: 14px;
}
.btn-action {
    display: inline-block;
    padding: 4px 10px;
    text-decoration: none;
    font-size: 12px;
    margin: 0 2px;
}
.btn-edit {
    background-color: #3498db;
    color: white;
}
.btn-delete {
    background-color: #e74c3c;
    color: white;
}
</style>

<?php include '../adm/top_adm/footer.php'; ?>