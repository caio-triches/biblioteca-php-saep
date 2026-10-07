# Biblioteca PHP

Sistema de gerenciamento de biblioteca em PHP + MySQL.

## Como rodar o projeto

### 1. Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) instalado (inclui Apache, MySQL e phpMyAdmin).

### 2. Coloque o projeto na pasta certa

Copie (ou clone) a pasta do projeto para dentro de `htdocs` do XAMPP:

```
C:\xampp\htdocs\biblioteca-php-saep
```

> No Linux/Mac o caminho costuma ser `/opt/lampp/htdocs/biblioteca-php-saep` ou similar, dependendo de onde o XAMPP foi instalado.

A estrutura final deve ficar assim:

```
C:\xampp\htdocs\biblioteca-php-saep\
├── auth\
├── categorias\
├── livros\
├── emprestimos\
├── config\
└── database\
    └── schema.sql
```

### 3. Inicie os serviços

1. Abra o **XAMPP Control Panel**.
2. Clique em **Start** ao lado de **Apache**.
3. Clique em **Start** ao lado de **MySQL**.
4. Os dois precisam ficar com status verde ("Running").

> Se o Apache não iniciar, geralmente é porque a porta 80 está ocupada por outro programa. Nesse caso, feche o programa que está usando a porta ou troque a porta do Apache em `Config > httpd.conf`.

### 4. Crie o banco de dados

1. No navegador, acesse `http://localhost/phpmyadmin`.
2. Clique na aba **SQL** (no topo).
3. Abra o arquivo `database/schema.sql` do projeto, copie todo o conteúdo e cole na caixa de texto do phpMyAdmin.
4. Clique em **Executar/Go**.
5. Confirme que o banco `biblioteca_php` apareceu na lista à esquerda, com as tabelas `categorias`, `usuario`, `livros` e `emprestimos` dentro dele.

### 5. Confira os dados de conexão

Abra o arquivo `config/database.php` e confira se os dados batem com a sua instalação do MySQL (no XAMPP padrão, usuário `root` e sem senha):

```php
self::$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_php;charset=utf8", "root");
```

Se o seu MySQL tiver senha, adicione como terceiro parâmetro:

```php
self::$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_php;charset=utf8", "root", "sua_senha");
```

### 6. Acesse o sistema

Com Apache e MySQL rodando, abra no navegador:

```
http://localhost/biblioteca-php-saep/auth/cadastro.php
```

Crie um usuário por ali. Depois disso você será redirecionado e pode navegar entre categorias, livros e empréstimos.

## Resolução de problemas comuns

| Problema | Causa provável | Solução |
|---|---|---|
| Página em branco / erro 404 | Projeto fora de `htdocs` ou nome de pasta errado na URL | Confirme o caminho `C:\xampp\htdocs\biblioteca-php-saep` e a URL digitada |
| "Connection refused" / erro de conexão com banco | MySQL não está rodando, ou dados errados em `database.php` | Verifique o status no XAMPP Control Panel e os dados de conexão |
| Apache não inicia | Porta 80 ocupada por outro programa | Feche o programa conflitante ou troque a porta do Apache |
| Erro "Unknown database biblioteca_php" | O `schema.sql` não foi executado | Repita o passo 4 |
| Alterações no código não aparecem no navegador | Está editando uma cópia fora de `htdocs` | Edite o arquivo diretamente dentro de `C:\xampp\htdocs\biblioteca-php-saep` |
