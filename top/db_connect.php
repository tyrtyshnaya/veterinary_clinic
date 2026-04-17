<?php

$host = "MySQL-8.4"; 
$username = "root";
$password = "";
$database = "TyrtyshnayaVet";

$db = mysqli_connect($host, $username, $password, $database);

mysqli_set_charset($db, "utf8mb4");
?>