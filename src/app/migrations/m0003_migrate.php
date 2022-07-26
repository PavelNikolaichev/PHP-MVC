<?php

class m0003_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table logins (
            id     int auto_increment unique,
            email  varchar(255) not null unique,
            first_name   varchar(255) not null,
            last_name    varchar(255) not null,
            password varchar(255) not null,
            created_date datetime not null default current_timestamp,
            constraint logins_pk
                primary key (id)
        )');
    }
}
