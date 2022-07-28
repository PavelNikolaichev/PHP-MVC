<?php

class m0004_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table if not exists sessions (
            id     int auto_increment unique primary key,
            user_id int,
            token  varchar(255) unique,
            last_login datetime not null default current_timestamp,
            user_ip int(10) unsigned  not null unique,
            foreign key (user_id) references logins(id) on delete cascade
        )');
    }
}
