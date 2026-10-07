<?php
require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

if(!isset($_GET['id'])){
    header('Location: /biblioteca-php-saep/emprestimos/index.php');
    exit;
}

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

try{
    $id = $_GET['id'];
    $data_entrega = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("update emprestimos set data_entrega = ? where id = ?");
    $stmt->execute([$data_entrega, $id]);

    header('Location: /biblioteca-php-saep/emprestimos/index.php');
    exit;
}catch(PDOException $e){
    echo "error: " . $e->getMessage();
}