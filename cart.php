<?php 
session_start();
include('top/db_connect.php');
include 'functions.php';

// Проверяем авторизацию
if (!isset($_SESSION['my_inside'])) {
    header('Location: login.php');
    exit();
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_query = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
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
    // Убираем пробелы и символы из цены
    $clean_price = preg_replace('/[^0-9]/', '', $row['price']);
    $row['price'] = (int)$clean_price;
    
    $cart_items[] = $row;
    $total = $total + ($row['price'] * $row['quantity']);
    $items_count = $items_count + $row['quantity'];
}

// СКИДКА (если сумма больше 5000 ₽)
$discount = 0;
if ($total >= 5000) {
    $discount = ($total/100)*5;
}
$final_total = $total - $discount;
?>

<div class="cart-page">
    <h1 class="cart-title">Корзина</h1>
    
    <div class="info-block">
        <div class="info-text">
            <span>Товары можно забрать в ветеринарной клинике по адресу: Московская ул., 104, Орёл</span>
        </div>
    </div>

    <div class="cart-container">
        <div class="cart-main"> 
            <?php if (count($cart_items) == 0): ?>
                <div class="empty-cart">
                    <p>Корзина пуста</p>
                    <a href="shop.php" class="btn-empty">Перейти к покупкам</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php for($i = 0; $i < count($cart_items); $i++): 
                        $item = $cart_items[$i];
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                        <div class="cart-item" data-cart-id="<?php echo $item['id']; ?>">
                            <div class="cart-item-image">
                                <?php if (!empty($item['img'])): ?>
                                    <img src="images/<?php echo $item['img']; ?>" alt="<?php echo $item['title']; ?>">
                                <?php else: ?>
                                    <div class="no-image">Нет фото</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cart-item-info">
                                <a href="shop_card.php?id=<?php echo $item['product_id']; ?>">
                                    <h3><?php echo $item['title']; ?></h3>
                                </a>
                                
                                <div class="cart-item-price">
                                    <span class="current-price"><?php echo $item['price']; ?> ₽</span>
                                </div>
                                
                                <div class="cart-item-quantity">
                                    <button class="qty-minus" data-cart-id="<?php echo $item['id']; ?>">-</button>
                                    <span class="qty-value" id="qty-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                    <button class="qty-plus" data-cart-id="<?php echo $item['id']; ?>" data-stock="<?php echo $item['stock']; ?>">+</button>
                                </div>
                                
                                <button class="remove-item" data-cart-id="<?php echo $item['id']; ?>">Удалить</button>
                            </div>
                            
                            <div class="cart-item-total">
                                <span class="total-price" id="total-<?php echo $item['id']; ?>"><?php echo $item_total; ?> ₽</span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (count($cart_items) > 0): ?>
        <div class="cart-sidebar">
            <div class="sidebar-item">
                <div class="sidebar-count"><?php echo $items_count; ?> товара</div>
                <div class="sidebar-total-old"><?php echo $total; ?> ₽</div>
            </div>
            
            <?php if ($discount > 0): ?>
            <div class="sidebar-discount">
                <span>Скидка за онлайн-покупку</span>
                <span>-<?php echo $discount; ?> ₽</span>
            </div>
            <?php else: ?>
                <div class="sidebar-discount no-discount">
                    <span>Скидки нет за покупку</span>
                </div>
            <?php endif; ?>
            
            <div class="sidebar-final">
                <span>Итого</span>
                <span class="final-price"><?php echo $final_total; ?> ₽</span>
            </div>
            
            <div class="checkout-btn">
                <a href="making_an_order.php" class="btn-checkout">Перейти к оформлению</a>
            </div>

            <div class="clear-cart-btn">
                <a href="cart_clear.php" onclick="return confirm('Вы уверены, что хотите очистить корзину?');" class="btn-clear">
                    <img src="images/delete.png" alt="Удалить"> Очистить корзину
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-title {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
}

.info-block {
    background: #e8f5e9;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 30px;
}

.info-text {
    font-size: 14px;
    line-height: 1.5;
}

.info-text strong {
    font-size: 15px;
}

.cart-container {
    display: flex;
    gap: 30px;
}

.cart-main {
    flex: 2;
}

.cart-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #eee;
}

.cart-item-image {
    width: 100px;
    height: 100px;
    background: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.cart-item-info {
    flex: 1;
}

.cart-item-info h3 {
    font-size: 16px;
    margin: 0 0 8px 0;
    color: #333;
}

.cart-item-info a {
    text-decoration: none;
}

.current-price {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 10px 0;
}

.cart-item-quantity button {
    width: 28px;
    height: 28px;
    background: #f0f0f0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.cart-item-quantity button:hover {
    background: #e0e0e0;
}

.qty-value {
    font-size: 16px;
    font-weight: 500;
    min-width: 30px;
    text-align: center;
}

.remove-item {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 13px;
    padding: 0;
}

.remove-item:hover {
    color: #f44336;
}

.cart-item-total {
    text-align: right;
    min-width: 120px;
}

.total-price {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    display: block;
}

.cart-sidebar {
    flex: 1;
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #eee;
    position: sticky;
    top: 20px;
    height: fit-content;
}

.sidebar-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.sidebar-count {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
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
    border-radius: 6px;
    margin: 15px 0;
    font-size: 14px;
    color: #4CAF50;
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
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.btn-checkout:hover {
    background: #45a049;
}

.empty-cart {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 8px;
}

.btn-empty {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 25px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.clear-cart-btn {
    margin-top: 15px;
    padding-top: 15px;
    text-align: center;
    border-top: 1px solid #eee;
}

.btn-clear {
    display: inline-block;
    background: none;
    color: #999;
    text-align: center;
    padding: 8px 15px;
    text-decoration: none;
    font-size: 14px;
}

.btn-clear:hover {
    color: #f44336;
}

.btn-clear img {
    width: 16px;
    height: 16px;
    vertical-align: middle;
    margin-right: 6px;
    opacity: 0.6;
}

.btn-clear:hover img {
    opacity: 1;
}
</style>

<script>
// Функция обновления всей корзины
function updateCart(cartId, newQuantity) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart_update.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Обновляем каждый товар в корзине
                    response.items.forEach(function(item) {
                        var itemElement = document.querySelector('.cart-item[data-cart-id="' + item.id + '"]');
                        if (itemElement) {
                            // Обновляем количество
                            var qtySpan = itemElement.querySelector('.qty-value');
                            if (qtySpan) qtySpan.textContent = item.quantity;
                            
                            // Обновляем итоговую цену товара
                            var totalSpan = itemElement.querySelector('.total-price');
                            if (totalSpan) totalSpan.textContent = item.item_total + ' ₽';
                        }
                    });
                    
                    // Обновляем общую информацию
                    var sidebarCount = document.querySelector('.sidebar-count');
                    if (sidebarCount) sidebarCount.innerHTML = response.items_count + ' товара';
                    
                    var sidebarTotalOld = document.querySelector('.sidebar-total-old');
                    if (sidebarTotalOld) sidebarTotalOld.innerHTML = response.total + ' ₽';
                    
                    // Обновляем блок скидки
                    var discountBlock = document.querySelector('.sidebar-discount');
                    if (response.discount > 0) {
                        if (discountBlock) {
                            discountBlock.classList.remove('no-discount');
                            discountBlock.innerHTML = '<span>Скидка за онлайн-покупку</span><span>-' + Math.round(response.discount) + ' ₽</span>';
                        }
                    } else {
                        if (discountBlock) {
                            discountBlock.classList.add('no-discount');
                            discountBlock.innerHTML = '<span>Скидки нет за покупку</span>';
                        }
                    }
                    
                    // Обновляем итоговую сумму
                    var finalPrice = document.querySelector('.final-price');
                    if (finalPrice) finalPrice.innerHTML = Math.round(response.final_total) + ' ₽';
                    
                    // Если корзина пуста - перезагружаем страницу
                    if (response.items.length === 0) {
                        location.reload();
                    }
                }
            } catch(e) {
                console.error('Ошибка:', e);
                location.reload();
            }
        }
    };
    xhr.send('cart_id=' + cartId + '&quantity=' + newQuantity);
}

// Функция удаления товара
function removeItem(cartId) {
    if (confirm('Удалить товар из корзины?')) {
        updateCart(cartId, 0);
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для кнопок "+"
    var plusButtons = document.querySelectorAll('.qty-plus');
    plusButtons.forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            var cartId = this.getAttribute('data-cart-id');
            var stock = parseInt(this.getAttribute('data-stock') || 999);
            var qtySpan = document.getElementById('qty-' + cartId);
            var currentQty = parseInt(qtySpan.textContent);
            
            if (currentQty < stock) {
                var newQty = currentQty + 1;
                updateCart(cartId, newQty);
            } else {
                showNotification('Недостаточно товара на складе', 'error');
            }
        };
    });
    
    // Обработчики для кнопок "-"
    var minusButtons = document.querySelectorAll('.qty-minus');
    minusButtons.forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            var cartId = this.getAttribute('data-cart-id');
            var qtySpan = document.getElementById('qty-' + cartId);
            var currentQty = parseInt(qtySpan.textContent);
            
            if (currentQty > 1) {
                var newQty = currentQty - 1;
                updateCart(cartId, newQty);
            } else if (currentQty === 1) {
                removeItem(cartId);
            }
        };
    });
    
    // Обработчики для кнопок "Удалить"
    var removeButtons = document.querySelectorAll('.remove-item');
    removeButtons.forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            var cartId = this.getAttribute('data-cart-id');
            removeItem(cartId);
        };
    });
    
    // Функция уведомлений
    function showNotification(message, type) {
        var notif = document.createElement('div');
        notif.textContent = message;
        var bgColor = type === 'error' ? '#f44336' : '#4CAF50';
        notif.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        document.body.appendChild(notif);
        setTimeout(function() { notif.remove(); }, 2000);
    }
});
</script>

<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
<?php include('top/footer.php'); ?>