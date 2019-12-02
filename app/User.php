<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users'; 
    protected $fillable = ['id', 'name', 'email', 'password'];

    public function categories()
    {

        return $this->hasMany(Category::class);

    }

}
