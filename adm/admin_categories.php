<?php
session_start();
include '../top/db_connect.php';

// Проверка авторизации админа
if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Обработка добавления категории
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $value = mysqli_real_escape_string($db, $_POST['value']);
    
    if (!empty($name) && !empty($value)) {
        // Проверяем, нет ли такой категории
        $check = mysqli_query($db, "SELECT id FROM categories WHERE value = '$value'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($db, "INSERT INTO categories (name, value) VALUES ('$name', '$value')");
            $success = "Категория добавлена!";
        } else {
            $error = "Категория с таким кодом уже существует!";
        }
    } else {
        $error = "Заполните оба поля!";
    }
}

// Обработка редактирования категории
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_result = mysqli_query($db, "SELECT * FROM categories WHERE id = $edit_id");
    $edit_category = mysqli_fetch_assoc($edit_result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $update_id = (int)$_POST['category_id'];
    $update_name = mysqli_real_escape_string($db, $_POST['name']);
    $update_value = mysqli_real_escape_string($db, $_POST['value']);
    
    mysqli_query($db, "UPDATE categories SET name = '$update_name', value = '$update_value' WHERE id = $update_id");
    
    header('Location: admin_categories.php');
    exit();
}

// Обработка удаления категории
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    
    // Проверяем, есть ли товары с этой категорией
    $check_products = mysqli_query($db, "SELECT id FROM shop WHERE category_id = $delete_id LIMIT 1");
    
    if (mysqli_num_rows($check_products) == 0) {
        mysqli_query($db, "DELETE FROM categories WHERE id = $delete_id");
        $success = "Категория удалена!";
    } else {
        $error = "Нельзя удалить категорию, в которой есть товары!";
    }
}

// Получаем список всех категорий
$categories = mysqli_query($db, "SELECT * FROM categories ORDER BY id");

include '../adm/top_adm/header.php';
?>

<div class="content">
    <h2 class="section-title">Управление категориями</h2>
    
    <!-- Сообщения -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <!-- Форма добавления/редактирования -->
    <div style="background: white; padding: 20px; margin-bottom: 30px; border: 1px solid #ddd;">
        <h3 style="margin: 0 0 15px 0;">
            <?php echo isset($edit_category) ? 'Редактирование категории' : '+ Добавить новую категорию'; ?>
        </h3>
        
        <?php if (isset($edit_category)): ?>
            <form method="POST">
                <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px; width: 35%;">
                            Название категории<br>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($edit_category['name']); ?>
                            " required style="width: 98%; padding: 8px; border: 1px solid #ccc;">
                        </td>
                        <td style="padding: 5px; width: 35%;">
                            Код (value)<br>
                            <input type="text" name="value" value="<?php echo htmlspecialchars($edit_category['value']); ?>
                            " required style="width: 98%; padding: 8px; margin: 8px; border: 1px solid #ccc;">
                        </td>
                        <td style="padding: 5px; vertical-align: bottom;">
                            <button type="submit" name="update_category" style="background: #27ae60; padding: 8px 20px;
                              margin: 8px; margin-left: 18px; border: none; cursor: pointer;">Сохранить</button>
                            <a href="admin_categories.php" style="margin-left: 10px;">Отмена</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <form method="POST">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px; width: 35%;">
                            Название категории<br>
                            <input type="text" name="name" placeholder="Например: Товары для птиц" required 
                            style="width: 98%; padding: 8px; border: 1px solid #ccc;">
                        </td>
                        <td style="padding: 5px; width: 35%;">
                            Код (value)<br>
                            <input type="text" name="value" placeholder="Например: bird" required 
                            style="width: 98%; padding: 8px; margin: 8px; border: 1px solid #ccc;">
                        </td>
                        <td style="padding: 5px; vertical-align: bottom;">
                            <button type="submit" name="add_category" style="background: #4a6fa5; padding: 8px 20px;  margin: 8px; 
                            margin-left: 18px; border: none; cursor: pointer;">Добавить</button>
                        </td>
                    </tr>
                </table>
            </form>
        <?php endif; ?>
    </div>

    
    <!-- Таблица категорий -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название категории</th>
                    <th>Код (value)</th>
                    <th>Количество товаров</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($categories) > 0): ?>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): 
                        // Считаем количество товаров в этой категории
                        $count_result = mysqli_query($db, "SELECT COUNT(*) as count FROM shop WHERE category_id = {$cat['id']}");
                        $count_data = mysqli_fetch_assoc($count_result);
                        $product_count = $count_data['count'];
                    ?>
                        <tr>
                            <td><?php echo $cat['id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td><code><?php echo $cat['value']; ?></code></td>
                            <td>
                                <?php if ($product_count > 0): ?>
                                    <span style="color: #27ae60;"><?php echo $product_count; ?> товаров</span>
                                <?php else: ?>
                                    <span style="color: #999;">0 товаров</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="admin_categories.php?edit=<?php echo $cat['id']; ?>" class="btn-action btn-edit">Редактировать</a>
                                <?php if ($product_count == 0): ?>
                                    <a href="admin_categories.php?delete=<?php echo $cat['id']; ?>" 
                                    class="btn-action btn-delete" onclick="return confirm('Удалить категорию?')">Удалить</a>
                                <?php else: ?>
                                    <span style="color: #999;" title="Нельзя удалить, есть товары">Есть товары</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Нет категорий. Создайте первую!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
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
    code {
        background: #f0f0f0;
        padding: 2px 6px;
    }
</style>

<?php include '../adm/top_adm/footer.php'; ?>