<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';

// Проверка авторизации
if (!isset($_SESSION['my_inside'])) {
    header('Location: login.php?error=Для заказа звонка нужно войти');
    exit;
}

$login = $_SESSION['current_user'];
$sql = "SELECT * FROM users WHERE login = '$login' LIMIT 1";
$result = dbquery($sql);
$user = dbfetcha($result);

include 'top/header.php';
?>

<div class="container" style="max-width: 600px;">
    <h2 class="section-title">Заказать обратный звонок</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" style="background: #dff0d8; color: #3c763d; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            Заявка отправлена! Мы перезвоним вам в ближайшее время.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error" style="background: #f2dede; color: #a94442; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="callback_save.php" style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #a0eea0;">
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Ваше ФИО:</label>
            <input type="text" name="name" value="<?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : ''; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Телефон:</label>
            <input type="text" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" required placeholder="+7 (999) 123-45-67" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Сообщение (необязательно):</label>
            <textarea name="message" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" placeholder="Опишите ваш вопрос..."></textarea>
        </div>
        
        <button type="submit" style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Заказать звонок
        </button>
    </form>
</div>

<?php include 'top/footer.php'; ?>