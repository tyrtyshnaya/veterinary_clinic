<?php
session_start();
include('top/db_connect.php');
include 'functions.php';
include('top/header.php');
add_to_history('Товары', 'shop.php');

// Определяем, авторизован ли пользователь
$is_logged_in = isset($_SESSION['my_inside']);

// Получаем корзину текущего пользователя для проверки (только если авторизован)
$cart_items = [];
if ($is_logged_in) {
    $login = $_SESSION['current_user'];
    $user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_data = mysqli_fetch_assoc($user_result);
        $user_id = $user_data['id'];
        $cart_result = mysqli_query($db, "SELECT product_id, quantity FROM cart WHERE user_id = $user_id");
        while ($row = mysqli_fetch_assoc($cart_result)) {
            $cart_items[$row['product_id']] = $row['quantity'];
        }
    }
}
 if ($is_logged_in): ?>
<script>
    document.body.setAttribute('data-logged-in', 'true');
</script>
<?php else: ?>
<script>
    document.body.setAttribute('data-logged-in', 'false');
</script>
<?php endif; ?>

<section class="shop-section">
    <div class="container">
        <h1 class="section-title">Товары и Услуги</h1>

        <div class="dropdown">
            <button class="dropdown_trigger">Категории</button>
            <div class="dropdown_content">                
                <a href="index.php#services">Услуги</a>
                <a href="shop.php?type=all">Все товары</a>
                <a href="shop.php?type=cat">Товары для кошек</a>
                <a href="shop.php?type=dog">Товары для собак</a>
            </div>
        </div>

        <div class="shop-grid">
            <?php
            $type = isset($_GET['type']) ? $_GET['type'] : 'all';
            
            if ($type == 'cat') {
                $query_shop = "SELECT * FROM shop WHERE category = 'cat' OR title LIKE '%кошек%' OR title LIKE '%кошка%'";
            } elseif ($type == 'dog') {
                $query_shop = "SELECT * FROM shop WHERE category = 'dog' OR title LIKE '%собак%' OR title LIKE '%собака%'";
            } elseif ($type == 'products') {
                $query_shop = "SELECT * FROM shop WHERE type = 'product' OR type IS NULL";
            } else {
                $query_shop = "SELECT * FROM shop";
            }

            $result_shop = mysqli_query($db, $query_shop);
            
            if (mysqli_num_rows($result_shop) > 0) {
                while ($shop = mysqli_fetch_assoc($result_shop)) {  
                    $product_id = $shop['id'];
                    $cart_quantity = isset($cart_items[$product_id]) ? $cart_items[$product_id] : 0;
                    $in_cart = ($cart_quantity > 0);
                    
                    echo '<div class="shop-card" data-id="' . $product_id . '">';
                    echo '<button class="fav-btn" data-id="' . $product_id . '">♡</button>';
                    
                    if (!empty($shop['img'])) {
                        echo '<img src="images/' . htmlspecialchars($shop['img']) . '" alt="' . htmlspecialchars($shop['title']) . '">';
                    } else {
                        echo '<div class="no-image">Нет фото</div>';
                    }
                    
                    echo '<div class="shop-card-content">';
                    echo '<a href="shop_card.php?id=' . $product_id . '"><h3>' . htmlspecialchars($shop['title']) . '</h3></a>';
                    echo '<p class="price">Цена: ' . htmlspecialchars($shop['price']) . ' ₽</p>';
                    
                    if ($is_logged_in) {
                        // АВТОРИЗОВАННЫЙ ПОЛЬЗОВАТЕЛЬ
                        if ($in_cart) {
                            // Товар в корзине - показываем кнопку "В корзине"
                            echo '<div class="add-to-cart-container" id="add-container-' . $product_id . '">';
                            echo '<button class="add-to-cart in-cart" data-id="' . $product_id . '" data-title="' . htmlspecialchars($shop['title']) . '">Товар в корзине</button>';
                            echo '</div>';
                        } else {
                            // Товара нет в корзине - показываем кнопку "В корзину"
                            echo '<div class="add-to-cart-container" id="add-container-' . $product_id . '">';
                            echo '<button class="add-to-cart" data-id="' . $product_id . '" data-title="' . htmlspecialchars($shop['title']) . '">Добавить в корзину</button>';
                            echo '</div>';
                        }
                    } else {
                        // НЕАВТОРИЗОВАННЫЙ ПОЛЬЗОВАТЕЛЬ - кнопка ведёт на вход
                        echo '<a href="login.php?redirect=shop.php&product=' . $product_id . '" class="add-to-cart-btn-link">Добавить в корзину</a>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-shop">Товаров нет. Загляните позже!</p>';
            }
            ?>
        </div>
    </div>
</section>

<style>
/* Выпадающее меню */
.dropdown {
    position: relative;
    display: inline-block;
    margin-bottom: 25px;
}

.dropdown_trigger {
    background: #4CAF50;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

.dropdown_trigger:hover {
    background: #45a049;
}

.dropdown_content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 180px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-radius: 8px;
    z-index: 100;
    margin-top: 5px;
}

.dropdown_content a {
    color: #333;
    padding: 12px 20px;
    text-decoration: none;
    display: block;
}

.dropdown.show .dropdown_content {
    display: block;
}

/* Карточки товаров */
.shop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.shop-card {
    position: relative;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
}

.shop-card img {
    width: 100%;
    height: 200px;
    object-fit: contain;
    background: #f5f5f5;
}

/* Кнопка сердечка */
.fav-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;
    border: 2px solid #e0e0e0;
    font-size: 20px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
}

.fav-btn.active {
    background: #f44336;
    border-color: #f44336;
    color: white;
}

/* Контент */
.shop-card-content {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.shop-card-content h3 {
    font-size: 16px;
    margin: 0 0 10px 0;
    height: 44px;
    overflow: hidden;
}

.price {
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
    margin: 10px 0;
}

/* Кнопка для авторизованных */
.add-to-cart {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.add-to-cart:hover {
    background: #45a049;
    transform: translateY(-1px);
}

.add-to-cart.in-cart {
    border-radius: 5px;
    border: 1px solid #45a049;
    background: #45a04900;
    color: #000000;
    cursor: default;
}

/* Кнопка для неавторизованных (ссылка на вход) */
.add-to-cart-btn-link {
    display: block;
    background: #4CAF50;
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 10px;
    font-size: 14px;
}

.add-to-cart-btn-link:hover {
    background: #45a049;
}
</style>

<script>
// Глобальная переменная для хранения избранного
let userFavorites = [];

// Функция загрузки избранного из БД
function loadFavorites() {
    <?php if ($is_logged_in): ?>
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_fav.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                userFavorites = JSON.parse(xhr.responseText);
                updateAllFavButtons();
                console.log('Избранное загружено:', userFavorites);
            } catch(e) {
                console.error('Ошибка парсинга:', e);
            }
        }
    };
    xhr.onerror = function() {
        console.error('Ошибка загрузки избранного');
    };
    xhr.send();
    <?php else: ?>
    userFavorites = [];
    <?php endif; ?>
}

// Функция обновления всех кнопок сердечек
function updateAllFavButtons() {
    var favBtns = document.querySelectorAll('.fav-btn');
    console.log('Найдено кнопок:', favBtns.length);
    console.log('Избранное:', userFavorites);
    
    favBtns.forEach(function(btn) {
        var productId = parseInt(btn.getAttribute('data-id'));
        if (userFavorites.includes(productId)) {
            btn.classList.add('active');
            btn.style.background = '#f44336';
            btn.style.borderColor = '#f44336';
            btn.style.color = 'white';
        } else {
            btn.classList.remove('active');
            btn.style.background = 'white';
            btn.style.borderColor = '#e0e0e0';
            btn.style.color = '#999';
        }
    });
}

// Функция добавления/удаления из избранного
function toggleFavorite(productId, button) {
    <?php if ($is_logged_in): ?>
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fav_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var result = JSON.parse(xhr.responseText);
                if (result.status === 'added') {
                    if (!userFavorites.includes(productId)) {
                        userFavorites.push(productId);
                    }
                    button.classList.add('active');
                    button.style.background = '#f44336';
                    button.style.borderColor = '#f44336';
                    button.style.color = 'white';
                    showNotification('✓ Товар добавлен в избранное', 'success');
                } else if (result.status === 'removed') {
                    var index = userFavorites.indexOf(productId);
                    if (index !== -1) {
                        userFavorites.splice(index, 1);
                    }
                    button.classList.remove('active');
                    button.style.background = 'white';
                    button.style.borderColor = '#e0e0e0';
                    button.style.color = '#999';
                    showNotification('✗ Товар удалён из избранного', 'info');
                }
            } catch(e) {
                console.error('Ошибка:', e);
            }
        }
    };
    xhr.onerror = function() {
        showNotification('Ошибка при сохранении', 'error');
    };
    xhr.send('product_id=' + productId);
    <?php else: ?>
    showNotification('Войдите в систему, чтобы добавлять в избранное', 'error');
    <?php endif; ?>
}

// Функция уведомлений
function showNotification(message, type) {
    var existingNotif = document.querySelector('.custom-notification');
    if (existingNotif) existingNotif.remove();
    
    var notif = document.createElement('div');
    notif.className = 'custom-notification';
    notif.textContent = message;
    
    var bgColor = '#4CAF50';
    if (type === 'error') bgColor = '#f44336';
    if (type === 'info') bgColor = '#ff9800';
    
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

// Добавляем CSS анимации
var style = document.createElement('style');
style.textContent = `
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
        cursor: pointer;
    }
    .fav-btn.active {
        background: #f44336 !important;
        border-color: #f44336 !important;
        color: white !important;
    }
`;
document.head.appendChild(style);

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    console.log('Страница загружена, загружаем избранное...');
    
    // Загружаем избранное
    loadFavorites();
    
    // Назначаем обработчики на кнопки сердечек
    setTimeout(function() {
        var favBtns = document.querySelectorAll('.fav-btn');
        favBtns.forEach(function(btn) {
            // Убираем старые обработчики
            var newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                var productId = parseInt(this.getAttribute('data-id'));
                toggleFavorite(productId, this);
            };
        });
    }, 100);
    
    // Выпадающее меню
    var dropdownBtn = document.querySelector('.dropdown_trigger');
    if (dropdownBtn) {
        dropdownBtn.onclick = function(e) {
            e.stopPropagation();
            this.closest('.dropdown').classList.toggle('show');
        };
    }
    
    document.onclick = function(e) {
        var dropdown = document.querySelector('.dropdown');
        if (dropdown && !e.target.closest('.dropdown')) {
            dropdown.classList.remove('show');
        }
    };
    
    // Кнопки корзины
    <?php if ($is_logged_in): ?>
    var addButtons = document.querySelectorAll('.add-to-cart:not(.in-cart)');
    addButtons.forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            var title = this.getAttribute('data-title');
            var $btn = this;
            
            if ($btn.classList.contains('in-cart')) {
                showNotification(title + ' уже в корзине', 'info');
                return;
            }
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cart_add.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                $btn.innerHTML = 'Товар в корзине';
                $btn.classList.add('in-cart');
                showNotification(title + ' добавлен в корзину', 'success');
            };
            xhr.send('product_id=' + id + '&quantity=1');
        };
    });
    <?php endif; ?>
});
</script>

<?php include('top/footer.php'); ?>