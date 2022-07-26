<?php

class m0004_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('create table if not exists sessions (
            id     int auto_increment unique,
            user_id int,
            token  varchar(255) unique,
            last_login datetime not null default current_timestamp,
            user_ip varchar(255) not null,
            foreign key (user_id) references users(id) on delete cascade,
            constraint sessions_pk
                primary key (id)
        )');
    }
}
