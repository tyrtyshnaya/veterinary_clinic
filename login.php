<?php
session_start();
include 'top/db_connect.php';
include "functions.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($login) && !empty($password)) {
        $result = mysqli_query($db, "SELECT * FROM users WHERE login='$login' LIMIT 1");
        
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            
            // Проверяем основной пароль ИЛИ временный
            $main_password_ok = ($data['password'] == md5($password));
            $temp_password_ok = (!empty($data['temp_password']) && $data['temp_password'] == md5($password));
            
            if ($main_password_ok || $temp_password_ok) {
                $_SESSION['my_inside'] = 1;
                $_SESSION['current_user'] = $login;
                
                // Если вход по временному паролю - просим сменить пароль
                if ($temp_password_ok) {
                    $_SESSION['temp_login'] = true;
                    header("Location: change_password.php");
                    exit;
                }
                
                // Очищаем временный пароль после успешного входа
                mysqli_query($db, "UPDATE users SET temp_password = NULL WHERE login='$login'");
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Неверный пароль";
            }
        } else {
            $error = "Пользователь не найден";
        }
    } else {
        $error = "Заполните все поля";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход в систему</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #a0eea0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #a0eea0;
            border-radius: 5px;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .error {
            color: #f44336;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <h2 class="section-title">Вход в систему</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
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
        
        <div class="register-link">
            <a href="forgot_password.php">Забыли пароль?</a> |
            <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>

</body>
</html>