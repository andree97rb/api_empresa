<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = "personal";
    protected $fillable = array('nombre', 'sueldo', 'fechaNacimiento', 'estado', 'idCategoria', 'idEmpresa');
    public $timestamps = false;

}
