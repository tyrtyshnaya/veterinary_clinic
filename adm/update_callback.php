<?php
session_start();
include '../top/db_connect.php';

if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($db, $_POST['status'] ?? '');
    $note = mysqli_real_escape_string($db, $_POST['note'] ?? '');
    $id = (int)$_POST['id'];
    
    mysqli_query($db, "UPDATE callback_requests SET status='$status', note='$note' WHERE id='$id'");
    header('Location: admin_panel.php');
    exit;
}

$result = mysqli_query($db, "SELECT * FROM callback_requests WHERE id='$id'");
$callback = mysqli_fetch_assoc($result);

if (!$callback) {
    header('Location: admin_panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Обработка звонка</title>
    <link rel="stylesheet" href="style_adm.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .info-block {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-block p {
            margin: 5px 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        a {
            color: #666;
            margin-left: 15px;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Обработка заявки #<?php echo $id; ?></h2>
        
        <div class="info-block">
            <p><strong>ФИО:</strong> <?php echo htmlspecialchars($callback['name'] ?? '-'); ?></p>
            <p><strong>Телефон:</strong> <?php echo htmlspecialchars($callback['phone']); ?></p>
            <p><strong>Сообщение:</strong> <?php echo htmlspecialchars($callback['message'] ?? '-'); ?></p>
            <p><strong>Дата создания:</strong> <?php echo date('d.m.Y H:i', strtotime($callback['created_at'])); ?></p>
        </div>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label>Статус:</label>
                <select name="status">
                    <option value="новый" <?php echo $callback['status'] == 'новый' ? 'selected' : ''; ?>>Новый</option>
                    <option value="в работе" <?php echo $callback['status'] == 'в работе' ? 'selected' : ''; ?>>В работе</option>
                    <option value="обработан" <?php echo $callback['status'] == 'обработан' ? 'selected' : ''; ?>>Обработан</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Примечание:</label>
                <textarea name="note" rows="4"><?php echo htmlspecialchars($callback['note'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit">Сохранить</button>
            <a href="admin_panel.php">Отмена</a>
        </form>
    </div>
</body>
</html>