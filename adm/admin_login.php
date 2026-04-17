<?php
session_start();
include '../top/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = mysqli_real_escape_string($db, $_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $error = '';
    
    if (empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $result = mysqli_query($db, "SELECT * FROM admin WHERE name = '$login' LIMIT 1");
        
        if ($result && mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['adm_id'] = $admin['id'];
                $_SESSION['adm_login'] = $admin['name'];
                header('Location: admin_panel.php');
                exit;
            } else {
                $error = 'Неверный пароль';
            }
        } else {
            $error = 'Администратор не найден';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход для администратора</title>
    <link rel="stylesheet" href="style_adm.css">
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container admin-login">
        <h2 class="section-title">Вход для администратора</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Регистрация успешна! Теперь можно войти.</div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Логин:</label>
                <input type="text" name="login" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Войти</button>
        </form>
        
        <div class="admin-links" style="text-align: center; margin-top: 20px;">
            <a href="../index.php">На главную</a>
        </div>
    </div>
</body>
</html>