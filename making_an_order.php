<?php
session_start();
include 'top/db_connect.php';
include 'functions.php';

//проверяем авторизацию
if (!isset($_SESSION['my_inside'])){
    header("Location: login.php");
    exit();
}
// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_query = mysqli_query($db, "SELECT id, fullname, phone, email FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_query);
$user_id = $user_data['id'];

include('top/header.php');

// Получаем товары из корзины
$cart_query = mysqli_query($db, "SELECT c.*, s.title, s.price, s.img, s.stock 
        FROM cart c 
        JOIN shop s ON c.product_id = s.id 
        WHERE c.user_id = $user_id");

$cart_items = array();
$total = 0;
$items_count = 0;

while ($row = mysqli_fetch_assoc($cart_query)) {
    $clean_price = preg_replace('/[^0-9]/', '', $row['price']);
    $row['price'] = (int)$clean_price;
    
    $cart_items[] = $row;
    $total = $total + ($row['price'] * $row['quantity']);
    $items_count = $items_count + $row['quantity'];
}

// СКИДКА
$discount = 0;
if ($total >= 5000) {
    $discount = ($total/100)*5;
}
$final_total = $total - $discount;
?>

<div class="cart-container">
    <div class="cart-main">
        <h1 class="cart-title">Оформление заказа</h1>
        
        <!-- ФОРМА ОФОРМЛЕНИЯ -->
        <form action="place_an_order.php" method="POST" id="order-form">
            
            <!-- Контактные данные -->
            <div class="order-block">
                <h3>Контактные данные</h3>
                <div class="form-group">
                    <label>ФИО *</label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user_data['fullname'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Телефон *</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Адрес самовывоза *</label>
                    <input type="text" name="address" value="Московская ул., 104, Орёл" required>
                    <small>Товары можно забрать в ветеринарной клинике</small>
                </div>
                <div class="form-group">
                    <label>Комментарий к заказу</label>
                    <textarea name="comment" rows="3" placeholder="Дополнительная информация..."></textarea>
                </div>
            </div>
            
            <!-- Выбор товаров -->
            <div class="order-block">
                <h3>Выберите товары для заказа</h3>
                <div class="select-all">
                    <label>
                        <input type="checkbox" id="select-all" checked> Выбрать все
                    </label>
                </div>
                
                <?php for($i = 0; $i < count($cart_items); $i++): 
                    $item = $cart_items[$i];
                    $item_total = $item['price'] * $item['quantity'];
                ?>
                    <div class="order-item" data-cart-id="<?php echo $item['id']; ?>">
                        <label class="order-item-checkbox">
                            <input type="checkbox" name="items[]" value="<?php echo $item['id']; ?>" class="item-checkbox" checked>
                        </label>
                        <div class="order-item-image">
                            <?php if (!empty($item['img'])): ?>
                                <img src="images/<?php echo $item['img']; ?>" alt="<?php echo $item['title']; ?>">
                            <?php else: ?>
                                <div class="no-image">Нет фото</div>
                            <?php endif; ?>
                        </div>
                        <div class="order-item-info">
                            <h4><?php echo $item['title']; ?></h4>
                            <div class="order-item-price"><?php echo $item['price']; ?> ₽</div>
                            <div class="order-item-quantity">Количество: <?php echo $item['quantity']; ?> шт.</div>
                        </div>
                        <div class="order-item-total">
                            <?php echo $item_total; ?> ₽
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <input type="hidden" name="total_amount" id="total-amount" value="<?php echo $final_total; ?>">
        </form>
    </div>
    
    <?php if (count($cart_items) > 0): ?>
    <div class="cart-sidebar">
        <div class="sidebar-item">
            <span class="sidebar-count" id="selected-count"><?php echo $items_count; ?></span>
            <span class="sidebar-total-old" id="selected-total-old"><?php echo $total; ?> ₽</span>
        </div>
        
        <?php if ($discount > 0): ?>
        <div class="sidebar-discount" id="discount-block">
            <span>Скидка за онлайн-покупку</span>
            <span id="discount-amount">-<?php echo $discount; ?> ₽</span>
        </div>
        <?php else: ?>
            <div class="sidebar-discount no-discount" id="discount-block">
                <span>Скидки нет за покупку</span>
            </div>
        <?php endif; ?>
        
        <div class="sidebar-final">
            <span>Итого</span>
            <span class="final-price" id="final-price"><?php echo $final_total; ?> ₽</span>
        </div>
        
        <div class="checkout-btn">
            <button type="submit" form="order-form" class="btn-checkout">ОФОРМИТЬ ЗАКАЗ</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.cart-container {
    max-width: 1200px; margin: 0 auto; padding: 20px; display: flex; gap: 30px;
}
.cart-main {flex: 2;}
.cart-title {font-size: 28px;margin-bottom: 25px;color: #333;}

.order-block {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #eee;
}
.order-block h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #4CAF50;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
.form-group input, .form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}
.form-group small {
    display: block;
    color: #999;
    font-size: 12px;
    margin-top: 5px;
}

.select-all {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.select-all label {
    cursor: pointer;
}

.order-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
    margin-bottom: 10px;
    align-items: center;
}
.order-item-checkbox {
    min-width: 30px;
}
.order-item-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
.order-item-image {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.order-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.order-item-info {
    flex: 1;
}
.order-item-info h4 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.order-item-price {
    color: #4CAF50;
    font-weight: bold;
}
.order-item-quantity {
    font-size: 12px;
    color: #666;
}
.order-item-total {
    font-weight: bold;
    min-width: 80px;
    text-align: right;
}

.cart-sidebar {
    flex: 1;
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #eee;
    position: sticky;
    top: 20px;
    height: fit-content;
}
.sidebar-item {
    text-align: left;
    padding: 10px; 
    border-bottom: 1px solid #eee;
}
.sidebar-count {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
    display: block;
}
.sidebar-total-old {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}
.sidebar-discount {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    background: #e8f5e9;
    border-radius: 8px;
    margin: 15px 0;
    font-size: 14px;
    color: #2ed119;
}
.sidebar-discount.no-discount {
    background: #f5f5f5;
    color: #999;
}
.sidebar-final {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    border-top: 1px solid #eee;
    margin-top: 10px;
}
.final-price {
    font-size: 24px;
    color: #4CAF50;
}
.checkout-btn {
    margin-top: 20px;
}
.btn-checkout {
    display: block;
    background: #4CAF50;
    color: white;
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    width: 100%;
    border: none;
    cursor: pointer;
    font-size: 16px;
}
.btn-checkout:hover {
    background: #45a049;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.item-checkbox');
    var selectAll = document.getElementById('select-all');
    var selectedCountSpan = document.getElementById('selected-count');
    var selectedTotalOldSpan = document.getElementById('selected-total-old');
    var finalPriceSpan = document.getElementById('final-price');
    var discountAmountSpan = document.getElementById('discount-amount');
    var discountBlock = document.getElementById('discount-block');
    
    // Данные о товарах из PHP
    var cartItems = <?php echo json_encode($cart_items); ?>;
    
    function updateTotals() {
        var selectedTotal = 0;
        var selectedCount = 0;
        
        checkboxes.forEach(function(cb, index) {
            if (cb.checked) {
                var item = cartItems[index];
                selectedTotal += item.price * item.quantity;
                selectedCount += item.quantity;
            }
        });
        
        // Пересчитываем скидку
        var discount = 0;
        if (selectedTotal >= 5000) {
            discount = selectedTotal * 0.05;
        }
        var finalTotal = selectedTotal - discount;
        
        // Обновляем отображение
        selectedCountSpan.innerHTML = selectedCount;
        selectedTotalOldSpan.innerHTML = selectedTotal + ' ₽';
        
        if (discount > 0) {
            discountBlock.classList.remove('no-discount');
            discountBlock.innerHTML = '<span>Скидка за онлайн-покупку</span><span>-' + Math.round(discount) + ' ₽</span>';
        } else {
            discountBlock.classList.add('no-discount');
            discountBlock.innerHTML = '<span>Скидки нет за покупку</span>';
        }
        
        finalPriceSpan.innerHTML = Math.round(finalTotal) + ' ₽';
        document.getElementById('total-amount').value = finalTotal;
    }
    
    // Обработчики
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateTotals);
    });
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(function(cb) {
            cb.checked = selectAll.checked;
        });
        updateTotals();
    });
    
    updateTotals();
});
</script>

<?php include 'top/footer.php'; ?>