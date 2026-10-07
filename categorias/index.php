<?php
require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];

        $stmt = $pdo->prepare('insert into categorias(nome, descricao) values(?, ?)');
        $stmt->execute([$nome, $descricao]);

        header('Location: /biblioteca-php-saep/categorias/index.php');
        exit; 
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }

}

try{
    $stmt = $pdo->prepare('select * from categorias');
    $stmt->execute();

    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Biblioteca</title>
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
        ul { list-style: none; }
        ul li {
            background: white; padding: 12px 20px; margin-bottom: 8px;
            border-radius: 5px; box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            display: flex; justify-content: space-between; align-items: center;
        }
        ul li a { margin-left: 10px; color: #3498db; text-decoration: none; }
        ul li a:last-child { color: #e74c3c; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #2c3e50; }
        input[type="text"], textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd;
            border-radius: 5px; margin-bottom: 15px; font-size: 14px;
        }
        button { background: #3498db; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #2980b9; }
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
    
    <h1>
        Categorias: 
    </h1>

    <ul>
       <?php foreach($categorias as $cat): ?>
        <li>
            <?= htmlspecialchars($cat['nome']) ?>
            <a href="editar.php?id=<?= $cat['id'] ?>">Editar</a>
            <a href="excluir.php?id=<?= $cat['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
        </li>
        <?php endforeach ?>
    </ul>

    <br><br>

    <section>

        <h1>Cadastro de novas categorias:</h1>

        <form method="post" action="/biblioteca-php-saep/categorias/index.php">
            <label>Nome:</label>
            <input type="text"
              placeholder="Digite o nome da Categoria"
              required
              name='nome'
            >

            <br><br>

             <label>Descrição</label>
            <textarea name="descricao" 
                id="descricao"
                placeholder="Digite um pouco sobre a categoria"
            ></textarea>

            <br><br>

            <button type="submit">Enviar</button>
            
        </form>

    </section>

    </div>

</body>
</html>