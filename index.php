<?php
session_start();
// Подключаемся к базе данных
include 'top/db_connect.php';

include 'functions.php';
add_to_history('Главная', 'index.php');
// Подключаем header
include 'top/header.php';



?>

<!-- Раздел "О нас" -->
<?php
$query = "SELECT * FROM `index` WHERE name = 'about'";
$result = mysqli_query($db, $query);
if ($row = mysqli_fetch_assoc($result)) {                                             //функция в PHP, которая выбирает одну строку данных из набора результатов
    echo '<section id="about" class="section">';
    echo '<div class="container">';
    echo '<h2 class="section-title">' . ($row['name'] == 'about' ? 'О нас' : '') . '</h2>';
    echo '<div class="about-content">';
    echo '<div class="about-text">' . $row['content'] . '</div>';
    if ($row['image']) {
        echo '<div class="about-image-container">';
        echo '<img src="images/' . $row['image'] . '" alt="О клинике" class="about-image">';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    echo '</section>';
}
?>

<!-- Раздел "Услуги" -->
    <div class="container">
        <h2 class="section-title">Ветеринарные услуги</h2>
        <p>Обратите внимание, услугу можно заказать только по номеру телефону или через "Заказать звонок".
            Напишите нам в сообщении какая процедура вас интересует и наш администратор обязательно свяжется с вами.</p>
        
        <div class="services-grid">
            <?php
            $query = "SELECT * FROM `service`";
            $result = mysqli_query($db, $query);
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div id="services" class="service-card">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                echo '<strong>' . htmlspecialchars($row['price']) . '</strong>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

<!-- Раздел "Преимущества" -->
<div class="container">
        <h2 class="section-title">Почему выбирают нас</h2>
        <div class="advantages-grid">
            <div class="advantage-card">
                <h3>Опытные врачи</h3>
                <p>Специалисты с многолетним стажем и любовью к животным</p>
            </div>
            <div class="advantage-card">
                <h3>Круглосуточно</h3>
                <p>Работаем без выходных и праздников</p>
            </div>
            <div class="advantage-card">
                <h3>Современное оборудование</h3>
                <p>Точная диагностика и эффективное лечение</p>
            </div>
            <div class="advantage-card">
                
                <h3>Индивидуальный подход</h3>
                <p>К каждому пациенту — особое внимание</p>
            </div>
        </div>
</div>

<!-- Раздел "Слайдер" -->
<div class="container">
    <h2 class="section-title">Фотогалерея</h2>
    
    <div class="css-slider">
        <?php
        $query = "SELECT * FROM `index` WHERE name = 'gallery'";
        $result = mysqli_query($db, $query);
        $slide_num = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $checked = ($slide_num == 1) ? 'checked' : '';
            echo '<input type="radio" name="slider" id="slide-' . $slide_num . '" ' . $checked . '>';
            $slide_num++;
        }
        ?>
        
        <div class="slider-images">
            <?php
            $query = "SELECT * FROM `index` WHERE name = 'gallery'";
            $result = mysqli_query($db, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="slide">';
                echo '<img src="images/' . $row['image'] . '" alt="' . $row['content'] . '">';
                echo '</div>';
            }
            ?>
        </div>
        
        <div class="slider-nav">
            <?php
            $query = "SELECT * FROM `index` WHERE name = 'gallery'";
            $result = mysqli_query($db, $query);
            $slide_num = 1;
            while ($row = mysqli_fetch_assoc($result)) {                                             
                echo '<label for="slide-' . $slide_num . '" class="nav-btn"></label>';
                $slide_num++;
            }
            ?>
        </div>
    </div>
</div>

<!-- Раздел "Контакты" -->
<?php
$query = "SELECT * FROM `index` WHERE name = 'contacts'";
$result = mysqli_query($db, $query);
if ($row = mysqli_fetch_assoc($result)) {
    echo '<section id="contacts" class="section contacts-section">';
    echo '<div class="container">';
    echo '<h2 class="section-title">Контакты</h2>';
    echo '<div class="contacts-content">';
    echo '<div class="contacts-info">' . $row['content'] . '</div>';
    if ($row['image']) {
        echo '<div class="contacts-map">';
        echo '<img src="images/' . $row['image'] . '" alt="Схема проезда" class="map-image">';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

// Закрываем соединение с БД
mysqli_close($db);

// Подключаем footer
include 'top/footer.php';
?>