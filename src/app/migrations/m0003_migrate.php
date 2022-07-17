<?php

class m0003_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table logins (
            id     int auto_increment unique,
            email  varchar(255) not null unique,
            name   varchar(255) not null,
            password varchar(255) not null,
            constraint logins_pk
                primary key (id)
        )');
    }
}
