<?php

session_start();
require_once __DIR__ . "\..\config\database.php";

try{
    $pdo = Connection::getConexao();

    $stmt = $pdo->prepare("select id, nome from categorias");
    $stmt->execute();

    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    die( "Erro de conexão com o banco de dados: ". $e->getMessage());
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $categoria_fav = $_POST["categoria_id"];

    try{
        $stmt = $pdo->prepare("insert into usuario (nome, email, senha, categoria_fav) values (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash, $categoria_fav]);
        $id = $pdo->lastInsertId();

        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nome'] = $nome;
        header("Location: /biblioteca-php-saep/index.php");
        exit;
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casdastro - Biblioteca</title>
</head>
<body>
    <h1>Bem vindo a pagina de cadastro da nossa biblioteca!</h1>

    <section>
        <form action="cadastro.php" method="post">
            <label>Nome: </label>
            <input type="text"
             placeholder="Digite seu nome"
             name="nome"
             required
             >

             <br><br>

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

            <label>Categoria:</label>
            <select name="categoria_id" id="categoria" required>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['nome']); ?>
                    </option>
                <?php endforeach ?>
            </select>

            <br><br>

            <button type="submit">Cadastrar</button>

            <br><br>

            <span>Já tem cadastro? <a href="login.php">Façã o login aqui!</a></span>
        </form>
    </section>
</body>
</html>
