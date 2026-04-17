<?php
session_start();
require_once  'check_auth.php';
include 'top/db_connect.php';
include 'functions.php';
add_to_history('Новость', 'news_card.php');
include 'top/header.php';



if (isset($_GET['id']) && is_numeric($_GET['id'])) {                                          //is_numeric-проверяет, является ли переданное значение числом или строкой
    $news_id = (int)$_GET['id']; 
    
    $query = "SELECT * FROM news WHERE id = $news_id";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $news = mysqli_fetch_assoc($result);                                                     //функция в PHP, которая выбирает одну строку данных из набора результатов
?>

<section class="news-detail-section">
    <div class="container">
        <div class="news-detail-card">
            <div class="news-detail-header">
                <h1 class="news-detail-title"><?php echo htmlspecialchars($news['title']); ?></h1>
            </div>
            
            <?php if (!empty($news['img'])): ?>
            <div class="news-detail-image">
                <img src="images/<?php echo htmlspecialchars($news['img']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
            </div>
            <?php endif; ?>
            
            <div class="news-detail-content">
                <p class="news-detail-short"><?php echo nl2br(htmlspecialchars($news['short_desk'])); ?></p>
                <div class="news-detail-full">
                    <?php echo nl2br(htmlspecialchars($news['long_desk'])); ?>
                </div>
            </div>
            
            <div class="news-detail-footer">
                <a href="news.php" class="back-button">← Все новости</a>
            </div>
        </div>
    </div>
</section>

<?php
    } else {
        // Новость не найдена
        echo '<section class="news-detail-section"><div class="container">';
        echo '<p class="error-message">Новость не найдена</p>';
        echo '<a href="news.php" class="back-button">Вернуться к новостям</a>';
        echo '</div></section>';
    }
} else {
    // ID не передан
    header('Location: news.php');
    exit();
}

mysqli_close($db);
include 'top/footer.php';
?>