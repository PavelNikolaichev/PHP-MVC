<?php

class m0002_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table if not exists users (
            id     int auto_increment unique,
            email  varchar(255) not null unique,
            name   varchar(255) not null,
            gender varchar(255) not null,
            status varchar(255) not null,
            constraint users_pk
                primary key (id)
        )');
    }
}
