<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    public $name;
    public $email;
    public $id;
    public $gender;
    public $status;

    public function __construct(string $email, string $name, string $gender, string $status, int $id = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->gender = $gender;
        $this->status = $status;
        // TODO: id check or something.
        $this->id = $id;
    }
}