<?php
require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

if(!isset($_GET['id'])){
    header('Location: /biblioteca-php-saep/livros/index.php');
    exit;
}

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

try{
    $id = $_GET['id'];

    $stmt = $pdo->prepare("delete from livros where id = ?");
    $stmt->execute([$id]);

   header('Location: /biblioteca-php-saep/livros/index.php');
   exit;
}catch(PDOException $e){
    echo "error: " . $e->getMessage();
}



