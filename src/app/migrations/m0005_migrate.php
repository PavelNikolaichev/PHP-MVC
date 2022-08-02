<?php

class m0005_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table if not exists attempts (
            id     int auto_increment unique,
            ip  int(10) unsigned  not null unique,
            attempts int not null,
            first_attempt_at datetime not null default current_timestamp,
            unbanned_at datetime default null,
            constraint logins_pk
                primary key (id)
        )');
    }
}
