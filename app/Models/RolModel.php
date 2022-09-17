<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\RolEntity;

class RolModel extends Model
{
    protected $table = "rol";
    protected $primaryKey = 'id';
    protected $returnType = RolEntity::class;
    protected $allowedFields = ["nombre", "descripcion"];
    public $rules = [
        'nombre' => [
            'rules' => 'required|is_unique[rol.nombre]',
            'errors' => [
                'required' => 'El campo nombre es requirido',
                'is_unique' => 'El campo nombre debe de ser único'
            ]
        ],
        'descripcion' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo descripcion es requirido',
            ]
        ],

    ];
}
