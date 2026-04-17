<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяем, есть ли переменная $admin (из admin_panel.php), если нет - берем из сессии
$admin_name = isset($admin['name']) ? $admin['name'] : ($_SESSION['adm_login'] ?? 'Администратор');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style_adm.css">
    <style>
        *{margin:0; padding:0;}
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .admin-container { 
            width: 100%; 
            font-family: sans-serif;
        }
        
        /* шапка и меню*/
        .admin-header {
            background-color: #5f87c3;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: normal;
        }
        .admin-header p {
            margin: 5px 0 0; 
            font-size: 13px;
        }
        .admin-header a {
            background-color: #c0392b;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            font-size: 14px;
        }
        
        /* МЕНЮ */
        .menu {
            background-color: #5f87c3;
            padding: 0 25px;
            border-top: 1px solid #5f87c3;
        }
        .menu ul {
            display: flex;
            justify-content: space-between;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .menu ul div {
            display: flex;
            gap: 30px;
        }
        .menu li a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 0;
            font-size: 14px;
        }
        
        /* КОНТЕНТ */
        .content {
            padding: 20px 25px;
        }
        
        .section-title {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: normal;
            border-bottom: 2px solid #8ca3c7;
            padding-bottom: 10px;
        }
        
        /* ТАБЛИЦЫ */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #ddd;
        }
        .admin-table th { 
            background-color: #5b7aa9;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: normal;
            font-size: 14px;
        }
        .admin-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .admin-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* СТАТУСЫ - БЕЗ СКРУГЛЕНИЙ */
        .status-новый, .status-Новый {
            color: #e74c3c;
            background-color: #fdeaea;
            padding: 4px 10px;
            display: inline-block;
            font-size: 12px;
        }
        .status-в_работе {
            color: #f39c12;
            background-color: #fef5e7;
            padding: 4px 10px;
            display: inline-block;
            font-size: 12px;
        }
        .status-обработан, .status-Подтвержден {
            color: #27ae60;
            background-color: #e9f7ef;
            padding: 4px 10px;
            display: inline-block;
            font-size: 12px;
        }
        .status-Отменен {
            color: #95a5a6;
            background-color: #ecf0f1;
            padding: 4px 10px;
            display: inline-block;
            font-size: 12px;
        }
        
        /* КНОПКИ */
        .btn-action {
            display: inline-block;
            padding: 6px 15px;
            background-color: #879bb8;
            color: white;
            text-decoration: none;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #9dc4de;
        }
        .btn-delete {
            background-color: #e74c3c;
        }
        .btn-confirm {
            background-color: #27ae60;
        }
        .btn-cancel {
            background-color: #e67e22;
        }
        
        .date-cell { 
            color: #666;
            font-size: 13px;
        }
        .phone-cell {
            font-weight: bold; 
            color: #333;
        }
        .message-cell {
            max-width: 200px; 
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            background: white;
            border: 1px solid #ddd;
        }
        .table-container {
            padding: 0;
        }
        .btn-add {
            background-color: #4a6fa5;
            color: white;
            padding: 8px 18px;
            text-decoration: none;
            font-size: 14px;
            margin: 15px;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <!-- Шапка -->
    <div class="admin-header">
        <div>
            <h1>Панель администратора</h1>
            <p>Добро пожаловать, <strong><?php echo htmlspecialchars($admin_name); ?></strong>!</p>
        </div>
        <a href="admin_logout.php">Выйти</a>
    </div>
    
    <!-- Меню -->
    <?php include 'menu_adm.php'; ?>
    
    <!-- Основной контент -->
    <div class="content">