<?php
session_start();
include 'top/db_connect.php';
include 'top/header.php';

// Проверяем авторизацию
if (!isset($_SESSION['my_inside'])) {
    header('Location: login.php');
    exit();
}

// Получаем ID пользователя
$login = $_SESSION['current_user'];
$user_result = mysqli_query($db, "SELECT id FROM users WHERE login='$login'");
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Получаем корзину пользователя
$cart_items = [];
$cart_result = mysqli_query($db, "SELECT product_id, quantity FROM cart WHERE user_id = $user_id");
while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[$row['product_id']] = $row['quantity'];
}

// Получаем избранные товары
$sql = "SELECT s.*, f.id as favorite_id 
        FROM favorites f 
        JOIN shop s ON f.product_id = s.id 
        WHERE f.user_id = $user_id 
        ORDER BY f.id DESC";
$result = mysqli_query($db, $sql);
?>

<div class="container">
    <h1 class="section-title">Понравившиеся товары</h1>
    
    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="empty-favorites">
            <p>У вас пока нет избранных товаров</p>
            <a href="shop.php" class="btn">← Перейти к покупкам</a>
        </div>
    <?php else: ?>
        <div class="favorites-grid">
            <?php while ($item = mysqli_fetch_assoc($result)): 
                $product_id = $item['id'];
                $cart_quantity = isset($cart_items[$product_id]) ? $cart_items[$product_id] : 0;
                $in_cart = ($cart_quantity > 0);
            ?>
                <div class="favorite-card" data-id="<?php echo $product_id; ?>">
                    <!-- Кнопка удаления из избранного -->
                    <button class="remove-fav" data-id="<?php echo $product_id; ?>" title="Удалить из избранного">✕</button>
                    
                    <div class="favorite-image">
                        <?php if (!empty($item['img'])): ?>
                            <img src="images/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php else: ?>
                            <div class="no-image">Нет фото</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="favorite-info">
                        <a href="shop_card.php?id=<?php echo $product_id; ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        </a>
                        <p class="price"><?php echo htmlspecialchars($item['price']); ?> ₽</p>
                        
                        <p class="stock">
                            <?php if ($item['stock'] > 0): ?>
                                <span class="in-stock">В наличии: <?php echo $item['stock']; ?> шт.</span>
                            <?php else: ?>
                                <span class="out-stock">Нет в наличии</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.section-title {
    font-size: 32px;
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.favorite-card {
    position: relative;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}

/* Кнопка удаления из избранного */
.remove-fav {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #f44336;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    z-index: 10;
}

.remove-fav:hover {
    background: #d32f2f;
}

.favorite-image {
    width: 100%;
    height: 200px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.favorite-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    padding: 10px;
}

.favorite-info {
    padding: 15px;
}

.favorite-info a {
    text-decoration: none;
    color: #333;
}

.favorite-info h3 {
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

.stock {
    margin: 5px 0;
    font-size: 13px;
}

.in-stock {
    color: #4CAF50;
}

.out-stock {
    color: #f44336;
}

.empty-favorites {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

.btn {
    display: inline-block;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    padding: 10px 25px;
    border-radius: 5px;
}

.btn:hover {
    background: #45a049;
}
</style>

<script src="favorites.js"></script>

<?php include 'top/footer.php'; ?>