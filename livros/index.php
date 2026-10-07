<?php
require_once __DIR__ . "/../auth/verificar_login.php";
require_once __DIR__ . "/../config/database.php";

try{
    $pdo = Connection::getConexao();
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

try{
    $stmt = $pdo->prepare('select * from categorias');
    $stmt->execute();

    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        select 
            livros.id,
            livros.titulo,
            livros.autor,
            categorias.nome as nome_categoria,
            emprestimos.id as emprestimo_ativo_id
        from livros
        left join categorias on livros.id_categoria = categorias.id
        left join emprestimos on livros.id = emprestimos.id_livro and emprestimos.data_entrega is null
    ");
    $stmt->execute();

    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
}


try{
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $sinopse = $_POST['sinopse'];
        $categoria = $_POST['categoria_id'];

        $stmt = $pdo->prepare("insert into livros(titulo, autor, sinopse, id_categoria) values (?, ?, ?, ?)");
        $stmt->execute([$titulo, $autor, $sinopse, $categoria]);

        header("Location: /biblioteca-php-saep/livros/index.php");
        exit;
    }
}catch(PDOException $e){
        echo "error: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros - Biblioteca</title>
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
        ul li a { margin-left: 10px; color: #3498db; text-decoration: none; }
        ul li span { color: #e74c3c; font-style: italic; margin-left: 10px; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #2c3e50; }
        input[type="text"], textarea, select {
            width: 100%; padding: 10px; border: 1px solid #ddd;
            border-radius: 5px; margin-bottom: 15px; font-size: 14px;
        }
        button { background: #3498db; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #2980b9; }
        .actions a:last-of-type { color: #e74c3c; }
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
        Livros Disponiveis:
    </h1>

    <ul>
       <?php foreach($livros as $liv): ?>
        <li>
            <?= htmlspecialchars($liv['titulo']) ?> - <?= htmlspecialchars($liv['autor']) ?> - <?= htmlspecialchars($liv['nome_categoria'] ?? 'Sem categoria') ?> 
            
            <a href="editar.php?id=<?= $liv['id'] ?>">Editar</a>
            <a href="excluir.php?id=<?= $liv['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>

            <?php if( $liv['emprestimo_ativo_id'] === null): ?>

                <a href="../emprestimos/novo.php?id=<?= $liv['id'] ?>">Emprestar</a> 

            <?php else: ?>

                <span>Indisponivel (emprestado)</span>

            <?php endif ?>

        </li>
        <?php endforeach ?>
    </ul>

    <br><br>

    <section>

        <h1>Cadastro de novos livros:</h1>

        <form method="post" action="index.php">
            <label>Titulo:</label>
            <input type="text"
              placeholder="Digite o titulo do Livro..."
              required
              name='titulo'
            >

            <br><br>

            <label>Autor:</label>
            <input name="autor"
            type="text" 
            id="autor"
            placeholder="Digite o nome do autor..."
            >

            <br><br>

            <label>Sinopse:</label>
            <textarea type="text"
            name="sinopse"
            placeholder="Escreve um pouco sobre o livro..."
            ></textarea>

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

            <button type="submit">Enviar</button>

    </form>
        
    </section>

    </div>

</body>
</html>