<?php

class m0005_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table attempts (
            id     int auto_increment unique,
            ip  int(10) unsigned  not null unique,
            email   varchar(255) not null,
            password varchar(255) not null,
            first_attempt_at datetime not null default current_timestamp,
            unbanned_at datetime not null default current_timestamp,
            constraint logins_pk
                primary key (id)
        )');
    }
}
