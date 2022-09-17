<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\UsuarioEntity;

class UsuarioModel extends Model
{
    protected $table = "usuario";
    protected $primaryKey = 'id';
    protected $returnType = UsuarioEntity::class;
    protected $allowedFields = ["nombre", "apellido", "nacimiento", "dui", "telefono", "rol_id", "departamento_id"];
    public $rules = [
        'nombre' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo nombre es requirido',
            ]
        ],
        'apellido' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo apellido es requirido',
            ]
        ],
        'nacimiento' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo nacimiento es requirido',
            ]
        ],
        'dui' => [
            'rules' => 'required|is_unique[usuario.dui]',
            'errors' => [
                'required' => 'El campo dui es requirido',
                'is_unique' => 'El campo dui debe de ser unico'
            ]
        ],
        'telefono' => [
            'rules' => 'required|is_unique[usuario.telefono]',
            'errors' => [
                'required' => 'El campo nombre es requirido',
                'is_unique' => 'El campo telefono debe de ser unico'
            ]
        ],
        'rol_id' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo rol es requirido',
            ]
        ],
        'departamento_id' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo departamento es requirido',
            ]
        ],

    ];
}
