<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\RolEntity;

class RolModel extends Model
{
    protected $table = "rol";
    protected $primaryKey = 'rol_id';
    protected $returnType = RolEntity::class;
    protected $allowedFields = ["rol_nombre", "rol_descripcion"];
    public $rules = [
        'rol_nombre' => [
            'rules' => 'required|is_unique[rol.nombre]',
            'errors' => [
                'required' => 'El campo rol_nombre es requerido',
                'is_unique' => 'El campo rol_nombre debe de ser único'
            ]
        ],
        'rol_descripcion' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo rol_descripcion es requerido',
            ]
        ],

    ];
}
