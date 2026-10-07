<?php 

require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}


try{

    $stmt = $pdo->prepare("
        select 
            livros.id as id_livro,
            livros.titulo,
            usuario.id as id_usuario,
            usuario.nome,
            emprestimos.data_entrega,
            emprestimos.data_retirada,
            emprestimos.id
        from emprestimos
        join livros on livros.id = emprestimos.id_livro
        join usuario on usuario.id = emprestimos.id_usuario
    ");
    $stmt->execute();

    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Empréstimos</title>
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
            flex-wrap: wrap;
        }
        ul li a { margin-left: 10px; color: #27ae60; text-decoration: none; font-weight: bold; }
        ul li span { color: #7f8c8d; font-style: italic; margin-left: 10px; }
        .back-link { display: inline-block; margin-top: 20px; color: #3498db; text-decoration: none; }
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

    <?php if ($emprestimos): ?>

        <h1>Lista de emprestimos da nossa biblioteca:</h1>

        <ul>
            <?php foreach($emprestimos as $emp): ?>
                <li>
                    <?= htmlspecialchars($emp['titulo']) ?> — <?= htmlspecialchars($emp['nome']) ?>
                    (retirado em <?= $emp['data_retirada'] ?>)

                    <?php if ($emp['data_entrega'] === null): ?>
                        <a href="devolver.php?id=<?= $emp['id'] ?>" onclick="return confirm('Confirma a devolução?')">Devolver</a>
                    <?php else: ?>
                        <span>Devolvido em <?= $emp['data_entrega'] ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>
        <h1>Sem emprestimos efetuados!</h1>
    <?php endif ?>

        <a class="back-link" href="/biblioteca-php-saep/livros/index.php">← Voltar para Livros</a>

    </div>
    
</body>
</html>