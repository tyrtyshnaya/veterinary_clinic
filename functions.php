<?php

require_once 'top/db_connect.php';

function dbquery($sql) {
    global $db;
    return mysqli_query($db, $sql);
}

function dbrows($result) {
    return mysqli_num_rows($result);
}

function dbfetcha($result) {
    return mysqli_fetch_assoc($result);
}

// Проверка авторизации
function check_user() {
    if (isset($_SESSION['my_inside']) && isset($_SESSION['current_user'])){
        return $_SESSION['current_user'];
    }
    return '';
}

// Добавление страницы в историю
function add_to_history($page_name, $page_url) {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
    
    // Добавляем в начало массива
    array_unshift($_SESSION['history'], [
        'page' => $page_name,
        'url' => $page_url,
        'time' => date('d.m.Y H:i:s')
    ]);
}

// Получение истории
function get_history() {
    return $_SESSION['history'] ?? [];
}


// Проверка на уникальность кода
function is_login_unique($login){
    $sql = "SELECT id FROM users WHERE login = '$login' LIMIT 1";
    $result = dbquery($sql);
    return !($result && dbrows($result) > 0);
}

?>