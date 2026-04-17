<?php
session_start();
include '../top/db_connect.php';

if(!isset($_SESSION['adm_id'])){
    header("Location: admin_login.php");
    exit();
}

//обработка формы
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $price = (int)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 'NULL';
    $description = mysqli_real_escape_string($db, $_POST['description']);

    //загрузка фото 
    $img = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['img']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            $new_name = time().'_'.rand(100,999).'.'.$ext;
            move_uploaded_file($_FILES['img']['tmp_name'], '../images/' . $new_name);
            $img = $new_name;
        }
    }
    
    $sql = "INSERT INTO shop (title, price, stock, category_id, description, img)
            VALUES ('$title', $price, $stock, $category_id, '$description', '$img')";

    if (mysqli_query($db, $sql)){
        header("Location: admin_shop.php?success=1");
        exit();
    } else {
        $error = "Ошибка: ".mysqli_error($db);
    }
}

include '../adm/top_adm/header.php';
?>
<div class="content">
    <h2 class="section-title">Добавление товара</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" style="max-width: 600px;">
        <div class="form-group">
            <label>Название товара *</label>
            <input type="text" name="title" required>
        </div>
        
        <div class="form-group">
            <label>Цена *</label>
            <input type="number" name="price" required>
        </div>
        
        <div class="form-group">
            <label>Количество на складе *</label>
            <input type="number" name="stock" value="0" required>
        </div>
        
        <div class="form-group">
            <label>Категория</label>
            <select name="category_id">
                <option value="">Выберите категорию</option>
                <?php
                $categories = mysqli_query($db, "SELECT * FROM categories ORDER BY name");
                while($cat = mysqli_fetch_assoc($categories)):
                ?>
                    <option value="<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="5"></textarea>
        </div>
        
        <div class="form-group">
            <label>Фото товара</label>
            <input type="file" name="img" accept="image/*">
        </div>
        
        <button type="submit">Сохранить</button>
        <a href="admin_shop.php" style="margin-left: 15px;">Отмена</a>
    </form>
</div>

<?php include '../adm/top_adm/footer.php'; ?>