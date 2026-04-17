<?php
session_start();
include '../top/db_connect.php';

if (!isset($_SESSION['adm_id'])) {
    header('Location: admin_login.php');
    exit();
}

$id = (int)$_GET['id'];

// Обработка формы с причиной отмены
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reason = mysqli_real_escape_string($db, $_POST['reason']);
    mysqli_query($db, "UPDATE orders SET status = 'Отменен', cancel_reason = '$reason' WHERE id = $id");
    header('Location: admin_order_view.php?id=' . $id);
    exit();
}

// Показываем форму для ввода причины
include '../adm/top_adm/header.php';
?>

<div class="content">
    <h2 class="section-title">Отмена заказа</h2>
    
    <div style="background: white; padding: 20px; max-width: 500px; border: 1px solid #ddd;">
        <p>Укажите причину отмены заказа:</p>
        <form method="POST">
            <textarea name="reason" rows="4" required style="width: 100%; padding: 8px; border: 1px solid #ccc;"></textarea>
            <br><br>
            <button type="submit" style="background: #228ee6; padding: 8px 20px; border: none; cursor: pointer;">Отменить заказ</button>
            <a href="admin_order_view.php?id=<?php echo $id; ?>">Отмена</a>
        </form>
    </div>
</div>

<?php include '../adm/top_adm/footer.php'; ?>