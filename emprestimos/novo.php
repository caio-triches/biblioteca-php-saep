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
            livros.id,
            livros.titulo
        from livros
        left join emprestimos on livros.id = emprestimos.id_livro and emprestimos.data_entrega is null
        where emprestimos.id is null
    ");
    $stmt->execute();

    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        $livros_get = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$livros_get){
            header('Location: /biblioteca-php-saep/livros/index.php');
            exit;
        }
        
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }
}

try{
    
    $stmt = $pdo->prepare("select usuario.nome, usuario.id from usuario");
        $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    try{
        $user = $_POST['usuario'];
        $livro = $_POST['livro'];

    $prazo_devolucao = date("Y-m-d H:i:s", strtotime("+30 days"));

    $stmt = $pdo->prepare("insert into emprestimos(id_usuario, id_livro, prazo_devolucao) values (?, ?, ?)");
    $stmt->execute([$user, $livro, $prazo_devolucao]);

    header("Location: /biblioteca-php-saep/emprestimos/index.php");
    exit;
    }catch(PDOException $e){
        echo "Erro: " . $e->getMessage();
        exit;
}
    
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Fazer Empréstimo</title>
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
        select {
            width: 100%; padding: 10px; border: 1px solid #ddd;
            border-radius: 5px; margin-bottom: 15px; font-size: 14px;
        }
        button { background: #27ae60; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #219a52; }
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

       <section>

       <h1>Formulario de emprestimos: </h1>

        <form action="novo.php" method="POST">

            <label>Usuarios:</label>
            <select name="usuario" id="usuario">
                <?php foreach($usuarios as $us): ?>
                <option value="<?= $us["id"] ?>">
                   <?= htmlspecialchars($us['nome']); ?>
                </option>
                <?php endforeach ?>
            </select>

            <br><br>

            <label>Livros:</label>
            <select name="livro" id="livro">
                <?php foreach($livros as $liv): ?>
                <option value="<?= $liv["id"] ?>"  <?= ($liv['id'] == $livros_get['id'] ? 'selected' : '')?>>
                   <?= htmlspecialchars($liv['titulo']); ?>
                </option>
                <?php endforeach ?>
            </select>

            <br><br>

            <button type="submit">Enviar</button>
        </form>

       </section>

        <a class="back-link" href="/biblioteca-php-saep/livros/index.php">← Voltar para Livros</a>

    </div>

</body>
</html>