<?php
session_start();
include '../top/db_connect.php';
include '../functions.php';

// Проверка авторизации администратора
if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Получаем данные администратора
$adm_id = $_SESSION['adm_id'];
$result = mysqli_query($db, "SELECT * FROM admin WHERE id = '$adm_id'");
$admin = mysqli_fetch_assoc($result);

// Получаем заявки на звонок 
$callbacks = mysqli_query($db, "SELECT * FROM callback_requests ORDER BY created_at DESC");

include 'top_adm/header.php';
?>


   
<h2 class="section-title">Заявки на обратный звонок</h2>
    
<div class="table-container">
    <?php if (!$callbacks || mysqli_num_rows($callbacks) == 0): ?>
        <div class="no-data">
            <p>Нет заявок на звонок</p>
        </div>
    <?php else: ?>
        <table class="admin-table">
             <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Сообщение</th>
                    <th>Статус</th>
                    <th>Примечание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($callbacks)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td class="date-cell"><?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($row['name'] ?? '-'); ?></td>
                    <td class="phone-cell"><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td class="message-cell"><?php echo htmlspecialchars($row['message'] ?? '-'); ?></td>
                    <td>
                        <?php
                        $status = $row['status'] ?? 'новый';
                        $statusClass = str_replace(' ', '_', $status);
                        ?>
                        <span class="status-<?php echo $statusClass; ?>"><?php echo $status; ?></span>
                    </td>
                    <td class="message-cell"><?php echo htmlspecialchars($row['note'] ?? '-'); ?></td>
                    <td>
                        <a href="update_callback.php?id=<?php echo $row['id']; ?>" class="btn-action">Обработать</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>