<?php
session_start();

unset($_SESSION['adm_id']);
unset($_SESSION['adm_login']);

header("Location: admin_login.php");
exit;

?>