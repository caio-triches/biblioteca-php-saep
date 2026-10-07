<?php

require_once __DIR__ . "/../auth/verificar_login.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Início</title>
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
        nav a {
            color: #ecf0f1;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        nav a:hover { color: #3498db; }
        nav .nav-links { display: flex; align-items: center; }
        nav .user-info { color: #ecf0f1; font-size: 14px; }
        nav .user-info a { color: #e74c3c; font-size: 14px; }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-5px); }
        .card h2 { color: #2c3e50; margin-bottom: 10px; }
        .card p { color: #7f8c8d; margin-bottom: 20px; }
        .card a {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
        }
        .card a:hover { background: #2980b9; }
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
        <h1>Bem-vindo à Biblioteca, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h1>
        <p>Escolha uma seção para navegar:</p>

        <div class="cards">
            <div class="card">
                <h2>📂 Categorias</h2>
                <p>Gerencie as categorias dos livros da biblioteca.</p>
                <a href="/biblioteca-php-saep/categorias/index.php">Acessar</a>
            </div>

            <div class="card">
                <h2>📖 Livros</h2>
                <p>Cadastre, edite e visualize os livros disponíveis.</p>
                <a href="/biblioteca-php-saep/livros/index.php">Acessar</a>
            </div>

            <div class="card">
                <h2>🔄 Empréstimos</h2>
                <p>Controle os empréstimos e devoluções de livros.</p>
                <a href="/biblioteca-php-saep/emprestimos/index.php">Acessar</a>
            </div>
        </div>
    </div>

</body>
</html>