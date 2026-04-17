<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<head>
    <title>Ветеринарная клиника Ветеринар и К</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include 'top/logo.php'; ?>
        <?php include 'top/menu.php'; ?>
        
        <div style="position: absolute; top: 20px; right: 40px;">
            <?php if (isset($_SESSION['adm_id'])): ?>
                
            <?php elseif (isset($_SESSION['my_inside'])): ?>
                <!-- Обычный пользователь -->
                <a href="cabinet.php" class="login-btn">
                    <span class="user-greeting"><?php echo $_SESSION['current_user']; ?></span>
                </a>
                
            <?php else: ?>
                <!-- Неавторизованный -->
                <a href="login.php" class="login-btn">Вход</a>
                <a href="register.php" class="login-btn" style="background: #4CAF50;">Регистрация</a>
            <?php endif; ?>
        </div>
    </header>
    <main>