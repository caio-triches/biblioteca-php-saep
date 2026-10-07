<?php

session_start();
session_destroy();

header("Location: /biblioteca-php-saep/auth/login.php");
exit;

?>

