<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: /biblioteca-php-saep/auth/login.php");
    exit;
}

?>