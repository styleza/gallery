CREATE USER 'tsoha'@'localhost' IDENTIFIED BY  '***';
GRANT USAGE ON * . * TO  'tsoha'@'localhost' IDENTIFIED BY  '***';
CREATE DATABASE IF NOT EXISTS  `tsoha` ;
GRANT ALL PRIVILEGES ON  `tsoha` . * TO  'tsoha'@'localhost';

create table users ( id int not null auto_increment, username varchar(80),password varchar(80),email varchar(80),primary key(id));
