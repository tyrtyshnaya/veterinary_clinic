<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';
include 'top/header.php';
?>

<div class="container" style="max-width: 500px;">
    <h2 class="section-title">Восстановление пароля</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" style="background: #dff0d8; color: #3c763d; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            Временный пароль создан! Запишите его: <strong><?php echo htmlspecialchars($_GET['success']); ?></strong>
            <p style="margin-top: 10px;"><a href="login.php" style="color: #4CAF50;">Перейти ко входу</a></p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error" style="background: #f2dede; color: #a94442; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="reset_password.php" style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #a0eea0;">
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Введите ваш логин:</label>
            <input type="text" name="login" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Введите ваш Email:</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        
        <button type="submit" style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Создать временный пароль
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="login.php" style="color: #4CAF50;">← Вернуться ко входу</a>
    </p>
</div>

<?php include 'top/footer.php'; ?>