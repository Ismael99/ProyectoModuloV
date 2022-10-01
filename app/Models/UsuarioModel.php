<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\UsuarioEntity;

class UsuarioModel extends Model
{
    protected $table = "usuario";
    protected $primaryKey = 'usuario_id';
    protected $returnType = UsuarioEntity::class;
    protected $createdField  = 'usuario_created_at';
    protected $updatedField  = 'usuario_updated_at';
    protected $beforeInsert = ['hashPassword'];

    protected $allowedFields = ["usuario_nombre", "usuario_apellido", "usuario_nacimiento", "usuario_dui", "usuario_telefono", "rol_id", "departamento_id", "usuario_password", "usuario_username"];
    public $rules = [
        'usuario_nombre' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo usuario_nombre es requerido',
            ]
        ],
        'usuario_apellido' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo usuario_apellido es requerido',
            ]
        ],
        'usuario_nacimiento' => [
            // TODO: add date format validation
            'rules' => 'required|valid_date[Y-m-d]',
            'errors' => [
                'required' => 'El campo usuario_nacimiento es requerido',
                'valid_date' => 'El campo usuario_nacimiento debe ser una fecha valida: Y-m-d',
            ]
        ],
        'usuario_dui' => [
            'rules' => 'required|is_unique[usuario.usuario_dui]',
            'errors' => [
                'required' => 'El campo usuario_dui es requerido',
                'is_unique' => 'El campo usuario_dui debe de ser unico'
            ]
        ],
        'usuario_username' => [
            'rules' => 'required|is_unique[usuario.usuario_username]',
            'errors' => [
                'required' => 'El campo usuario_username es requerido',
                'is_unique' => 'El campo usuario_username debe de ser unico'
            ]
        ],
        'usuario_telefono' => [
            'rules' => 'required|is_unique[usuario.usuario_telefono]',
            'errors' => [
                'required' => 'El campo usuario_nombre es requerido',
                'is_unique' => 'El campo usuario_telefono debe de ser único'
            ]
        ],
        'rol_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo rol es requerido',
            ]
        ],
        'departamento_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo departamento es requerido',
            ]
        ],
        'usuario_password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo usuario_password es requerido',
            ]
        ]
    ];
    
    public $rulesUpdate = [
        // 'usuario_nacimiento' => [
        //     // TODO: add date format validation
        //     'rules' => 'valid_date[Y-m-d]',
        //     'errors' => [
        //         'valid_date' => 'El campo usuario_nacimiento debe ser una fecha valida: Y-m-d',
        //     ]
        // ],
        'usuario_dui' => [
            'rules' => 'is_unique[usuario.usuario_dui]',
            'errors' => [
                'is_unique' => 'El campo usuario_dui debe de ser unico'
            ]
        ],
        'usuario_username' => [
            'rules' => 'is_unique[usuario.usuario_username]',
            'errors' => [
                'is_unique' => 'El campo usuario_username debe de ser unico'
            ]
        ],
        'usuario_telefono' => [
            'rules' => 'is_unique[usuario.usuario_telefono]',
            'errors' => [
                'is_unique' => 'El campo usuario_telefono debe de ser único'
            ]
        ],
    ];

    protected function hashPassword(array $user)
    {
        $user['data']['usuario_password'] = password_hash($user['data']['usuario_password'], PASSWORD_DEFAULT);

        return $user;
    }
}
