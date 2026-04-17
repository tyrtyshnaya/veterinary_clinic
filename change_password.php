<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';

// Проверяем, что это вход по временному паролю
if (!isset($_SESSION['temp_login']) || !isset($_SESSION['current_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Заполните все поля';
    } elseif ($new_password != $confirm_password) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($new_password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } else {
        $login = $_SESSION['current_user'];
        $hashed = md5($new_password);
        
        $sql = "UPDATE users SET password = '$hashed', temp_password = NULL WHERE login = '$login'";
        dbquery($sql);
        
        unset($_SESSION['temp_login']);
        header('Location: cabinet.php?success=1');
        exit;
    }
}

include 'top/header.php';
?>

<div class="container change-password" style="max-width: 500px;">
    <h2 class="section-title">Смена пароля</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #a0eea0;">
        <p style="margin-bottom: 20px;">Вы вошли по временному паролю. Пожалуйста, установите новый пароль:</p>
        
        <div class="form-group">
            <label>Новый пароль:</label>
            <input type="password" name="new_password" required>
        </div>
        
        <div class="form-group">
            <label>Подтвердите пароль:</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <button type="submit">Сохранить пароль</button>
    </form>
</div>

<?php include 'top/footer.php'; ?>