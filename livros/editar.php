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
}catch(PDOException $e){
    echo "error: " . $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!isset($_GET['id'])){
    header('Location: /biblioteca-php-saep/livros/index.php');
    exit;
    }

    try{
        $id = $_GET['id'];

        $stmt = $pdo->prepare("select * from livros where id = ?");
        $stmt->execute([$id]);

        $livros = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$livros){
            header('Location: /biblioteca-php-saep/livros/index.php');
            exit;
        }
        
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }
}


try{
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $sinopse = $_POST['sinopse'];
        $categoria = $_POST['categoria_id'];

        $stmt = $pdo->prepare("update livros set titulo = ?, autor = ?, sinopse = ?, id_categoria = ? where id = ?");
        $stmt->execute([$titulo, $autor, $sinopse, $categoria, $id]);

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
    <title>Edição de Livros - Biblioteca</title>
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
        input[type="text"], textarea, select {
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

        <h1>Editando o livro  <?= htmlspecialchars($livros['titulo']) ?>:</h1>

        <form method="POST" action="editar.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($livros['id']) ?>" >        

            <label>Titulo:</label>
            <input type="text"
              placeholder="Digite o titulo do Livro..."
              required
              name='titulo'
              value="<?= htmlspecialchars($livros['titulo']) ?>"
            >

            <br><br>

            <label>Autor:</label>
            <input name="autor"
            type="text" 
            id="autor"
            placeholder="Digite o nome do autor..."
             value="<?= htmlspecialchars($livros['autor']) ?>"
            >

            <br><br>

            <label>Sinopse:</label>
            <textarea type="text"
            name="sinopse"
            placeholder="Escreve um pouco sobre o livro..."
            ><?= htmlspecialchars($livros['sinopse']) ?></textarea>

            <br><br>

            <label>Categoria:</label>
            <select name="categoria_id" id="categoria" required>
                
                <?php if( $livros["id_categoria"] == null): ?>
                    <option value="" disabled selected>Selecione</option>
                <?php endif ?>

                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $livros['id_categoria']) ? 'selected' : ''  ?> >
                        <?= htmlspecialchars($cat['nome']); ?>
                    </option>
                <?php endforeach ?>
            </select>

            <br><br>

            <button type="submit">Enviar</button>

            <br><br>
            <a class="back-link" href="/biblioteca-php-saep/livros/index.php">← Voltar para Livros</a>

    </form>

    </div>

</body>
</html>