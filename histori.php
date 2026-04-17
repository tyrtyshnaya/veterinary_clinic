<?php
session_start();

include 'top/db_connect.php';
include 'functions.php';

add_to_history('История посещений', 'histori.php');

$history = get_history();
?>
<!DOCTYPE html>
<html>
<head>
    <title>История посещений</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .history-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #a0eea0;
        }
        
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .history-table th {
            background-color: #b0f8b4;
            color: #2c3e50;
            padding: 12px;
            text-align: left;
        }
        
        .history-table td {
            padding: 12px;
            border-bottom: 1px solid #d0f0d0;
        }
        
        .history-table tr:hover {
            background-color: #f0faf0;
        }
        
        .history-table a {
            color: #22e632;
            text-decoration: none;
        }
        
        .history-table a:hover {
            text-decoration: underline;
        }
        
        .empty-history {
            text-align: center;
            padding: 60px;
            color: #666;
            background: #f8f8f8;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .clear-btn {
            display: inline-block;
            padding: 8px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .clear-btn:hover {
            background-color: #d32f2f;
        }
        
        .user-info {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: right;
        }
        
        .user-info a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 5px 15px;
            background: #f44336;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php include 'top/header.php'; ?>
    
    <div class="history-container">
        <h1 class="section-title">История посещений</h1>
        
        <?php if (empty($history)): ?>
            <div class="empty-history">
                <p>История посещений пуста</p>
                <p>Посетите страницы сайта, чтобы они появились здесь</p>
            </div>
        <?php else: ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Страница</th>
                        <th>Время</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $index => $item): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($item['page']); ?></td>
                        <td><?php echo $item['time']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="text-align: right; margin-top: 20px;">
                <a href="clear_histori.php" class="clear-btn" onclick="return confirm('Очистить историю?')">Очистить историю</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'top/footer.php'; ?>
</body>
</html>