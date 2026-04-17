<?php
session_start();
require_once 'top/db_connect.php';
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    
    $error = '';
    
    // Проверка на уникальность логина
    if (!empty($login)){
        $sql = "SELECT login FROM users WHERE login='$login' LIMIT 1";
        $result = dbquery($sql);
        if ($result && dbrows($result)){
            $error .= "Пользователь с таким логином уже существует<br>";
        }
    }
    
    // Проверка email на уникальность
    if (!empty($email)){
        $sql = "SELECT email FROM users WHERE email='$email' LIMIT 1";
        $result = dbquery($sql);
        if ($result && dbrows($result)){
            $error .= "Пользователь с таким email уже существует<br>";
        }
    }
    
    // Проверка паролей
    if (!empty($password1) && $password1 != $password2) {
        $error .= "Пароли не совпадают<br>";
    }
    
    // Проверка длины пароля
    if (strlen($password1) < 6) {
        $error .= "Пароль должен быть не менее 6 символов<br>";
    }
    
    // Проверка заполнения полей
    if (empty($login) || empty($email) || empty($password1)) {
        $error .= "Заполните все обязательные поля<br>";
    }
    
    if (!empty($error)){
        include "top/header.php";
        echo "<div class='container'>";
        echo "<div class='error-message'>$error</div>";
        echo '<p style="text-align: center;"><a href="register.php" style="color: #4CAF50;">Попробовать еще раз</a></p>';
        echo "</div>";
        include "top/footer.php";
        exit;
    }
    
    if (!empty($login) && !empty($password1)){
        $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (login, email, phone, fullname, password) VALUES (
            '$login', 
            '$email', 
            '$phone', 
            '$fullname', 
            '$hashedPassword'
        )";
        $result = dbquery($sql);
        if (!$result){
            include "top/header.php";
            echo "<div class='container'>";
            echo "<div class='error-message'>Ошибка базы данных</div>";
            echo "</div>";
            include "top/footer.php";
        } else {
            $_SESSION['my_inside'] = 1;
            $_SESSION['current_user'] = $login;
            $_SESSION['user_id'] = mysqli_insert_id($db);
            header('Location: index.php');
            exit;
        }
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Регистрация</title>
        <link rel="stylesheet" href="style.css">
        <script src="valid.js"></script>
        <style>
            .register-container {
                max-width: 500px;
                margin: 60px auto;
                background: white;
                padding: 40px;
                border-radius: 12px;
                border: 1px solid #a0eea0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .section-title {
                text-align: center;
                color: #2c3e50;
                margin-bottom: 30px;
                font-size: 24px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            label {
                display: block;
                margin-bottom: 8px;
                color: #2c3e50;
                font-weight: 500;
            }
            
            input {
                width: 100%;
                padding: 12px;
                border: 1px solid #a0eea0;
                border-radius: 5px;
                font-size: 14px;
                transition: border-color 0.3s;
                box-sizing: border-box;
            }
            
            input:focus {
                outline: none;
                border-color: #4CAF50;
                box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
            }
            
            button {
                width: 100%;
                padding: 12px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 500;
                transition: background-color 0.3s;
            }
            
            button:hover {
                background-color: #45a049;
            }
            
            .error {
                color: #f44336;
                margin-bottom: 20px;
                text-align: center;
                padding: 10px;
                background-color: #ffebee;
                border-radius: 5px;
                border-left: 3px solid #f44336;
            }
            
            .register-link {
                text-align: center;
                margin-top: 25px;
                padding-top: 20px;
                border-top: 1px solid #e0e0e0;
            }
            
            .register-link a {
                color: #4CAF50;
                text-decoration: none;
                margin: 0 10px;
            }
            
            .register-link a:hover {
                text-decoration: underline;
            }
            
            .required {
                color: #f44336;
            }
            
            .hint {
                font-size: 12px;
                color: #999;
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="register-container">
            <h2 class="section-title">Регистрация</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            
            <form name="register" method="post" onSubmit="return formValidation();">
                <div class="form-group">
                    <label>Логин <span class="required">*</span></label>
                    <input type="text" name="login" maxlength="32" required>
                    <div class="hint">От 3 до 50 символов</div>
                </div>
                
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" maxlength="100" required>
                </div>
                
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" maxlength="20" placeholder="+79991234567">
                    <div class="hint">Необязательное поле</div>
                </div>
                
                <div class="form-group">
                    <label>Полное имя</label>
                    <input type="text" name="fullname" maxlength="100">
                    <div class="hint">Необязательное поле</div>
                </div>
                
                <div class="form-group">
                    <label>Пароль <span class="required">*</span></label>
                    <input type="password" name="password1" maxlength="32" required>
                    <div class="hint">Минимум 6 символов</div>
                </div>
                
                <div class="form-group">
                    <label>Повторите пароль <span class="required">*</span></label>
                    <input type="password" name="password2" maxlength="32" required>
                </div>
                
                <button type="submit">Зарегистрироваться</button>
            </form>
            
            <div class="register-link">
                <a href="login.php">Войти</a> |
                <a href="forgot_password.php">Забыли пароль?</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>