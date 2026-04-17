<?php
session_start();

include 'top/db_connect.php';

include 'functions.php';
add_to_history('Новостная страница', 'news.php');

include 'top/header.php';

?>

<section class="news-section">
    <div class="container">
        <h1 class="section-title">Новости и акции</h1>
        <h2 class="news-subtitle">Будьте в курсе событий нашей клиники</h2>
        
        <div class="news-grid">
            <?php
            $query_news = "SELECT * FROM news";
            $result_news = mysqli_query($db, $query_news);
            
            if (mysqli_num_rows($result_news) > 0) {
                while ($news = mysqli_fetch_assoc($result_news)) {                                         //функция в PHP, которая выбирает одну строку данных из набора результатов
                    echo '<div class="news-card">';
                    
                    // Проверяем есть ли фото
                    if (!empty($news['img'])) {
                        echo '<img src="images/' . htmlspecialchars($news['img']) . '" alt="' . htmlspecialchars($news['title']) . '">';
                    } else {
                        echo '<div class="no-image">Нет фото</div>';
                    }
                    
                    echo '<div class="news-card-content">';
                    echo '<h3>' . htmlspecialchars($news['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($news['short_desk']) . '</p>';
                    echo '<a href="news-card.php?id=' . $news['id'] . '">Читать далее →</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-news">Новостей пока нет. Загляните позже!</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
mysqli_close($db);
include 'top/footer.php';
?>