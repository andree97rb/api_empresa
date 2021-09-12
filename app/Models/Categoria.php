<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categoria";
    protected $fillable = array('nombre');
    public $timestamps = false;

    public function personal()
    {
        return $this->hasMany('App\Models\Personal', 'idCategoria');
    }
}
