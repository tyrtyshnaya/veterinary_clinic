<?php
session_start();

include 'top/db_connect.php';
include 'functions.php';

// проверка авторизации
if(!isset($_SESSION['my_inside'])){
    header("Location: login.php");
    exit;
}

// получаем данные пользователя
$login = $_SESSION['current_user'];
$sql = "SELECT * FROM users WHERE login='$login' LIMIT 1";
$result = dbquery($sql);
$user = dbfetcha($result);

include 'top/header.php';
?>

<div class="container">
    <h2 class="section-title">Личный кабинет</h2>
    
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; border: 1px solid #a0eea0;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h3>Добро пожаловать, <span style="color: #4CAF50;"><?php echo htmlspecialchars($user['login']); ?></span>!</h3>
        </div>
        
        <!-- Личные данные (скрывающийся блок) -->
        <div style="border: 1px solid #a0eea0; border-radius: 8px; margin-bottom: 15px;">
            <div onclick="togglePersonal()" style="background: #f5faf5; padding: 12px 20px; cursor: pointer; font-weight: bold;">
                Личные данные
            </div>
            <div id="personalData" style="display: none; padding: 20px; border-top: 1px solid #a0eea0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px;"><strong>Логин:</strong></td>
                        <td style="padding: 8px;"><?php echo htmlspecialchars($user['login']); ?></td>
                    </tr>
                    <?php if (!empty($user['email'])): ?>
                    <tr>
                        <td style="padding: 8px;"><strong>Email:</strong></td>
                        <td style="padding: 8px;"><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($user['phone'])): ?>
                    <tr>
                        <td style="padding: 8px;"><strong>Телефон:</strong></td>
                        <td style="padding: 8px;"><?php echo htmlspecialchars($user['phone']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($user['fullname'])): ?>
                    <tr>
                        <td style="padding: 8px;"><strong>Полное имя:</strong></td>
                        <td style="padding: 8px;"><?php echo htmlspecialchars($user['fullname']); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- История посещения-->
        <div style="border: 1px solid #a0eea0; border-radius: 8px; margin-bottom: 15px;">
            <a href="histori.php" style="display: block; background: #f5faf5; padding: 12px 20px; text-decoration: none; color: #333; font-weight: bold;">
                История посещения
            </a>
        </div>

        <!-- Понравившиеся товары-->
        <div style="border: 1px solid #a0eea0; border-radius: 8px; margin-bottom: 15px;">
            <a href="favorite.php" style="display: block; background: #f5faf5; padding: 12px 20px; text-decoration: none; color: #333; font-weight: bold;">
                Понравившиеся товары
            </a>
        </div>
         <!--Мои заказы -->
        <div style="border: 1px solid #a0eea0; border-radius: 8px; margin-bottom: 15px;">
            <a href="order_history.php" style="display: block; background: #f5faf5; padding: 12px 20px; text-decoration: none; color: #333; font-weight: bold;">
                Мои заказы
            </a>
        </div>
        
        <!-- Кнопка выхода -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="logout.php" style="display: inline-block; padding: 10px 30px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">
                Выйти
            </a>
        </div>
    </div>
</div>

<script>
function togglePersonal() {
    var div = document.getElementById('personalData');
    if (div.style.display === "none") {
        div.style.display = "block";
    } else {
        div.style.display = "none";
    }
}
</script>

<?php
include 'top/footer.php';
?>