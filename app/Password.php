<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $table = 'passwords'; 
    protected $fillable = ['title', 'password',];

    public function category()
    {

        return $this->belongsTo(Category::class);

    }


}
