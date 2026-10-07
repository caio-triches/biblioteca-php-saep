<?php
require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!isset($_GET['id'])){
    header('Location: /biblioteca-php-saep/categorias/index.php');
    exit;
    }

    try{
        $id = $_GET['id'];

        $stmt = $pdo->prepare("select * from categorias where id = ?");
        $stmt->execute([$id]);

        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$categoria){
            header('Location: /biblioteca-php-saep/categorias/index.php');
            exit;
        }
        
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];

    try{
        $stmt = $pdo->prepare("update categorias set nome = ?, descricao = ? where id = ?");
        $stmt->execute([$nome, $descricao, $id]);

        header('Location: /biblioteca-php-saep/categorias/index.php');
        exit;
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Categorias - Biblioteca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        nav {
            background: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a { color: #ecf0f1; text-decoration: none; margin: 0 15px; font-size: 16px; }
        nav a:hover { color: #3498db; }
        nav .nav-links { display: flex; align-items: center; }
        nav .user-info { color: #ecf0f1; font-size: 14px; }
        nav .user-info a { color: #e74c3c; font-size: 14px; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        h1 { color: #2c3e50; margin-bottom: 15px; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #2c3e50; }
        input[type="text"], textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd;
            border-radius: 5px; margin-bottom: 15px; font-size: 14px;
        }
        button { background: #3498db; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #2980b9; }
        .back-link { display: inline-block; margin-top: 15px; color: #3498db; text-decoration: none; }
    </style>
</head>
<body>

    <nav>
        <div class="nav-links">
            <a href="/biblioteca-php-saep/index.php"><strong>📚 Biblioteca</strong></a>
            <a href="/biblioteca-php-saep/categorias/index.php">Categorias</a>
            <a href="/biblioteca-php-saep/livros/index.php">Livros</a>
            <a href="/biblioteca-php-saep/emprestimos/index.php">Empréstimos</a>
        </div>
        <div class="user-info">
            Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?> |
            <a href="/biblioteca-php-saep/auth/logout.php">Sair</a>
        </div>
    </nav>

    <div class="container">
    <h1>Editando a categoria: <?= htmlspecialchars($categoria['nome'])?> </h1>

    <section>
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($categoria['id']) ?>" >

            <label>Nome:</label>
            <input type="text"
              placeholder="Digite o nome da Categoria"
              required
              name='nome'
              value="<?= htmlspecialchars($categoria['nome']) ?>"
            >

            <br><br>

             <label>Descrição</label>
            <textarea name="descricao" 
            id="descricao"
            placeholder="Digite um pouco sobre a categoria"
            ><?= htmlspecialchars($categoria['descricao'])?></textarea>

            <br><br>

            <button type="submit">Enviar</button>

            <br><br>

            <a class="back-link" href="/biblioteca-php-saep/categorias/index.php">← Voltar para Categorias</a>

        </form>
    </section>

    </div>

</body>
</html>

