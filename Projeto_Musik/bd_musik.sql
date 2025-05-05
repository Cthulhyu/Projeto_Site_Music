create database SiteMusica;

use SiteMusica;
create table `sitemusica`.`Usuario`(
	`id` int not null auto_increment,
    `nome` varchar(100) not null,
    `senha` varchar (45) not null,
    `email` varchar (45) not null,
    `genero_music` varchar (100) not null,
    primary key (`id`)
);

insert into Usuario (nome, senha, email, genero_music) values
    ('Gabriel', 123456, 'gabriel.boaventura@sempreceub.com', 'Rock, MPB, Choro');
insert into Usuario (nome, senha, email, genero_music) values
    ('Cecília', 123456, 'cecilia.formiga@sempreceub.com', 'Hinos, MPB, Erucito, Rock');


Select * from Usuario
