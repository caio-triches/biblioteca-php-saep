create database biblioteca_php;
use biblioteca_php;

create table categorias (
    id          int primary key auto_increment,
    nome        varchar(100) not null,
    descricao   varchar(2500) not null
);

create table usuario (
    id             int primary key auto_increment,
    nome           varchar(100) not null,
    email          varchar(100) not null unique,
    senha          varchar(100) not null,
    categoria_fav  int,
    foreign key (categoria_fav) references categorias(id) on delete set null
);

create table livros (
    id             int primary key auto_increment,
    id_categoria   int,
    titulo         varchar(100) not null,
    autor          varchar(100) not null,
    sinopse        varchar(255),
    data_cadastro  timestamp default current_timestamp,
    foreign key (id_categoria) references categorias(id) on delete set null
);

create table emprestimos (
    id                int primary key auto_increment,
    id_usuario        int not null,
    id_livro          int not null,
    data_retirada     timestamp default current_timestamp,
    data_entrega      timestamp null,
    prazo_devolucao   timestamp null,
    foreign key (id_usuario) references usuario(id) on delete restrict,
    foreign key (id_livro)   references livros(id)  on delete restrict
);

-- categorias

insert into categorias (nome, descricao) values
('Ficção', 'É uma categoria que costuma brincar com a nossa imaginação. Tem livros clássicos como: Harry Potter, Senhor dos Anéis, entre outros gigantes mundiais.'),
('Romance', 'Categoria que conta sobre alguns casos de amor. Pode contar uma decepção, uma alegria, uma história triste de amor, muito emocionante em todos os sentidos. Está ficando muito forte entre os jovens atualmente! Contendo vários livros com séries e filmes sendo sucessos mundiais.'),
('Terror', 'Acostumada a deixar inúmeras pessoas sem dormir, ela é marcada por histórias de arrepiar, às vezes até histórias reais, o que deixa ainda mais assustador. Pode misturar alguns aspectos de ficção e romance, para deixar a história mais interessante!');

-- livros

insert into livros (titulo, autor, sinopse, id_categoria) values
('Dom Casmurro', 'Machado de Assis', 'A história de Bentinho e sua obsessão por Capitu.',
    (select id from categorias where nome = 'Romance' limit 1)),
('1984', 'George Orwell', 'Um retrato distópico de vigilância totalitária.',
    (select id from categorias where nome = 'Ficção' limit 1)),
('Sapiens', 'Yuval Noah Harari', 'Uma breve história da humanidade.',
    null);
