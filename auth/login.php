<?php

session_start();
require_once __DIR__ . "/../config/database.php";

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    die( "Erro de conexão com o banco de dados: ". $e->getMessage());
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email =  $_POST['email'];
    $senha =$_POST['senha'];

    try{
        $stmt = $pdo->prepare("select * from usuario where email = ?");
        $stmt->execute([$email]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$usuario){
            echo "Email ou senha incorretos!";
            exit;
        }

        if(password_verify($senha, $usuario['senha'])){
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: /biblioteca-php-saep/index.php');
            exit;
        }else{
            echo "Email ou senha incorretos!";
        }

    }catch(PDOException $e){
        echo "Email ou senha incorretos!" . $e->getMessage();
        exit;
    }

    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca</title>
</head>
<body>
    <h1>Bem vindo a pagina de login!</h1>

    <section>
        <form action="login.php" method="post">

            <label>Email: </label>
            <input type="email"
             placeholder="Digite seu email"
             name="email"
             required
            >

            <br><br>

            <label>Senha: </label>
            <input type="password" 
             placeholder="Digite a sua senha"
             name="senha"
             required
            >

            <br><br>

            <button type="submit">Logar</button>

            <br><br>

           <span>Não tem cadastro? <a href="cadastro.php">Cadastre-se Aqui</a></span>
        </form>
    </section>
</body>
</html>
