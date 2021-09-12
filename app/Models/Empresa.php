<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = "empresa";
    protected $fillable = array('razonsocial', 'ruc', 'tipo');
    public $timestamps = false;

    public function personal()
    {
        return $this->hasMany('App\Models\Personal', 'idEmpresa');
    }
}