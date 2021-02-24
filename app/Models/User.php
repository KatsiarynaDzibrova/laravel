<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username',  'email', 'password', 'last_login_at', 'email_verified_at'];

}
