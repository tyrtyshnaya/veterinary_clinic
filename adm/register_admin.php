<?php
session_start();
include '../top/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $admin_code = $_POST['admin_code'] ?? '';  

    if ($admin_code != 'vet_admin'){
        $error = 'Неверный код администратора';
    } elseif (empty($login) || empty($password)){
        $error = 'Заполните все поля!';
    } else {
        // проверка уникальности
        $check = mysqli_query($db, "SELECT id FROM admin WHERE name = '$login'");
        
        if (mysqli_num_rows($check) > 0){  
            $error = 'Такой логин уже существует';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO admin (name, password) VALUES ('$login', '$hashed')";
            $result = mysqli_query($db, $sql);
            
            if ($result) {
                header("Location: admin_login.php?success=1");
                exit;
            } else {
                $error = 'Ошибка базы данных: ' . mysqli_error($db);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация администратора</title>
    <link rel="stylesheet" href="style_adm.css">
    <script src='valid_adm.js'></script>
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container admin-register">
        <h2 class="section-title">Регистрация администратора</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Администратор успешно зарегистрирован!</div>
        <?php endif; ?>
        
        <form name="register" method="POST" onSubmit="return formValidation();">
            <div class="form-group">
                <label>Логин:</label>
                <input type="text" name="login" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Код администратора:</label>
                <input type="password" name="admin_code" required>
            </div>
            
            <button type="submit">Зарегистрировать</button>
        </form>
        
        <div class="admin-links" style="text-align: center; margin-top: 20px;">
            <a href="admin_login.php">Вход для администратора</a> |
            <a href="../index.php">На главную</a>
        </div>
    </div>
</body>
</html>