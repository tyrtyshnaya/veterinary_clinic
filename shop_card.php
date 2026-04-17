<?php
session_start();
include('top/db_connect.php');
include('top/header.php');

// Получаем ID товара
$id = $_GET['id'];

// Берём товар из базы
$result = mysqli_query($db, "SELECT * FROM shop WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Товар не найден";
    include('top/footer.php');
    exit();
}

// Проверяем, есть ли товар в корзине
$cart_quantity = 0;
if (isset($_SESSION['my_inside'])) {
    $login = $_SESSION['current_user'];
    $user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_data = mysqli_fetch_assoc($user_result);
        $user_id = $user_data['id'];
        $cart_result = mysqli_query($db, "SELECT quantity FROM cart WHERE user_id = $user_id AND product_id = $id");
        if ($cart_result && mysqli_num_rows($cart_result) > 0) {
            $cart_row = mysqli_fetch_assoc($cart_result);
            $cart_quantity = $cart_row['quantity'];
        }
    }
}
$in_cart = ($cart_quantity > 0);
?>

<div class="container">
    <a href="shop.php" class="back">← Назад к товарам</a>
    <div class="product-page">
        <!-- Фото с сердечком -->
        <div class="product-img-wrapper">
            <div class="product-img">
                <?php if ($product['img']): ?>
                    <img src="images/<?php echo $product['img']; ?>" alt="<?php echo $product['title']; ?>">
                <?php else: ?>
                    <div class="no-img">Нет фото</div>
                <?php endif; ?>
                <!-- Сердечко внутри картинки -->
                <button class="fav-btn" data-id="<?php echo $product['id']; ?>">♡</button>
            </div>
        </div>
        
        <!-- Информация -->
        <div class="product-info">
            <h1><?php echo $product['title']; ?></h1>
            
            <!-- Цена и кнопка в одну строку -->
            <div class="price-row">
                <span class="price"><?php echo $product['price']; ?> ₽</span>
                
                <!-- Кнопка "В корзину" (показывается если товара нет в корзине) -->
                <div id="add-container" style="<?php echo $in_cart ? 'display: none;' : ''; ?>">
                    <button class="cart-btn" data-id="<?php echo $product['id']; ?>">В корзину</button>
                </div>
                
                <!-- Блок + и - (показывается если товар уже в корзине) -->
                <div id="qty-container" style="<?php echo $in_cart ? '' : 'display: none;'; ?>">
                    <div class="quantity-control">
                        <button class="minus-item" data-id="<?php echo $product['id']; ?>">-</button>
                        <span class="item-count" id="item-count"><?php echo $cart_quantity; ?></span>
                        <button class="plus-item" data-id="<?php echo $product['id']; ?>">+</button>
                    </div>
                </div>
            </div>
            
            <!-- Остаток -->
            <div>
                <?php if ($product['stock'] > 0): ?>
                    <span>В наличии: <?php echo $product['stock']; ?> шт.</span>
                <?php elseif ($product['stock'] == 0): ?>
                    <span>Нет в наличии</span>
                <?php endif; ?>
            </div>

            <!-- Описание -->
            <div class="desc">
                <h3>Описание</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'] ?: 'Нет описания')); ?></p>
            </div>
        </div>
    </div>
</div>
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-page {
    display: flex;
    gap: 50px;
    background: white;
    padding: 40px;
    border-radius: 15px;
    margin: 40px 0;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

/* Левая колонка с картинкой */
.product-img-wrapper {
    flex: 1;
}

.product-img {
    position: relative;  
    background: #ffffff;
    border-radius: 10px;
    text-align: center;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
}

/* Кнопка-сердечко на картинке */
.fav-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: white;
    border: 2px solid #e0e0e0;
    font-size: 24px;
    cursor: pointer;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #999;
    z-index: 10;
}

.fav-btn:hover {
    border-color: #f44336;
    color: #f44336;
    transform: scale(1.1);
}

.fav-btn.active {
    background: #f44336;
    border-color: #f44336;
    color: white;
}

/* Правая колонка */
.product-info {
    flex: 1;
}

.product-info h1 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
    line-height: 1.3;
}

/* Цена и кнопка в одной строке */
.price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.price {
    font-size: 28px;
    font-weight: bold;
    color: #4CAF50;
}

.cart-btn {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.cart-btn:hover {
    background: #45a049;
    transform: translateY(-2px);
}

/* Количество (+ и -) */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quantity-control button {
    width: 35px;
    height: 35px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
}

.quantity-control button:hover {
    background: #ddd;
}

.item-count {
    font-size: 18px;
    font-weight: bold;
    min-width: 30px;
    text-align: center;
}

/* Описание */
.desc {
    margin: 25px 0;
}

.desc h3 {
    font-size: 18px;
    color: #555;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #4CAF50;
    display: inline-block;
}

.desc p {
    line-height: 1.8;
    color: #666;
    text-align: justify;
    margin-top: 15px;
}

/* Назад */
.back {
    display: inline-block;
    color: #4CAF50;
    text-decoration: none;
    margin-top: 20px;
    font-size: 14px;
}

.back:hover {
    text-decoration: underline;
}
</style>

<script>
// ID текущего товара
const currentProductId = <?php echo $product['id']; ?>;

// Функция уведомлений (без jQuery)
function showNotification(message, type = 'success') {
    var existingNotif = document.querySelector('.custom-notification');
    if (existingNotif) existingNotif.remove();
    
    var notif = document.createElement('div');
    notif.className = 'custom-notification';
    notif.textContent = message;
    
    var bgColor = '#4CAF50';
    if (type === 'error') bgColor = '#f44336';
    
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        font-family: Arial, sans-serif;
        font-size: 14px;
    `;
    
    document.body.appendChild(notif);
    setTimeout(function() {
        notif.style.animation = 'fadeOut 0.3s ease';
        setTimeout(function() { notif.remove(); }, 300);
    }, 2000);
}

// Функция обновления состояния сердечка
function updateHeartButton(isFavorite) {
    var heartBtn = document.querySelector('.fav-btn');
    if (!heartBtn) return;
    
    if (isFavorite) {
        heartBtn.classList.add('active');
        heartBtn.style.background = '#f44336';
        heartBtn.style.borderColor = '#f44336';
        heartBtn.style.color = 'white';
    } else {
        heartBtn.classList.remove('active');
        heartBtn.style.background = 'white';
        heartBtn.style.borderColor = '#e0e0e0';
        heartBtn.style.color = '#999';
    }
}

// Загрузка статуса избранного из БД
function loadFavoriteStatus() {
    console.log('Загрузка статуса избранного для товара:', currentProductId);
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_fav.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var favorites = JSON.parse(xhr.responseText);
                console.log('Получен список избранного:', favorites);
                var isFavorite = favorites.includes(currentProductId);
                console.log('Товар в избранном?', isFavorite);
                updateHeartButton(isFavorite);
            } catch(e) {
                console.error('Ошибка парсинга:', e);
            }
        }
    };
    xhr.onerror = function() {
        console.error('Ошибка загрузки избранного');
    };
    xhr.send();
}

// Обработчик клика по сердечку
function initHeartButton() {
    var heartBtn = document.querySelector('.fav-btn');
    if (!heartBtn) return;
    
    heartBtn.onclick = function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var isActive = this.classList.contains('active');
        console.log('Клик по сердечку, текущее состояние:', isActive);
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'fav_add.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var result = JSON.parse(xhr.responseText);
                    console.log('Ответ от сервера:', result);
                    
                    if (result.status === 'added') {
                        updateHeartButton(true);
                        showNotification('✓ Товар добавлен в избранное', 'success');
                    } else if (result.status === 'removed') {
                        updateHeartButton(false);
                        showNotification('✗ Товар удалён из избранного', 'success');
                    }
                } catch(e) {
                    console.error('Ошибка:', e);
                }
            }
        };
        xhr.send('product_id=' + currentProductId);
    };
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    console.log('Страница товара загружена');
    loadFavoriteStatus();
    initHeartButton();
    
    // КОД ДЛЯ КОРЗИНЫ (упрощенный)
    var cartBtn = document.querySelector('.cart-btn');
    if (cartBtn) {
        cartBtn.onclick = function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart_add.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('add-container').style.display = 'none';
                document.getElementById('qty-container').style.display = 'block';
                document.getElementById('item-count').textContent = '1';
                showNotification('Товар добавлен в корзину', 'success');
            };
            xhr.send('product_id=' + id + '&quantity=1');
        };
    }
    
    // Плюс и минус
    var plusBtn = document.querySelector('.plus-item');
    var minusBtn = document.querySelector('.minus-item');
    
    if (plusBtn) {
        plusBtn.onclick = function() {
            var id = this.getAttribute('data-id');
            var countSpan = document.getElementById('item-count');
            var currentCount = parseInt(countSpan.textContent);
            var newCount = currentCount + 1;
            countSpan.textContent = newCount;
            updateCart(id, newCount);
        };
    }
    
    if (minusBtn) {
        minusBtn.onclick = function() {
            var id = this.getAttribute('data-id');
            var countSpan = document.getElementById('item-count');
            var currentCount = parseInt(countSpan.textContent);
            if (currentCount > 1) {
                var newCount = currentCount - 1;
                countSpan.textContent = newCount;
                updateCart(id, newCount);
            } else if (currentCount === 1) {
                removeFromCart(id);
            }
        };
    }
    
    function updateCart(id, quantity) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart_update_simple.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Обновляем количество на странице
                    document.getElementById('item-count').textContent = response.quantity;
                    // Обновляем цену
                    var priceElement = document.querySelector('.price');
                    if (priceElement) {
                        var basePrice = <?php echo $product['price']; ?>;
                        var newTotal = basePrice * response.quantity;
                        // Обновляем отображение в блоке цены (если нужно)
                    }
                }
            } catch(e) {
                console.error('Ошибка:', e);
            }
        }
    };
    xhr.send('product_id=' + id + '&quantity=' + quantity);
}

    function removeFromCart(id) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cart_update_simple.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('add-container').style.display = 'block';
                document.getElementById('qty-container').style.display = 'none';
                showNotification('Товар удалён из корзины', 'success');
            }
        };
        xhr.send('product_id=' + id + '&quantity=0');
    }
});
</script>

<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
.fav-btn {
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}
.fav-btn.active {
    background: #f44336 !important;
    border-color: #f44336 !important;
    color: white !important;
}
.fav-btn:hover {
    transform: scale(1.1);
}
</style>

<?php include('top/footer.php'); ?>