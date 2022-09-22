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

    protected $allowedFields = ["usuario_nombre", "usuario_apellido", "usuario_nacimiento", "usuario_dui", "usuario_telefono", "rol_id", "departamento_id", "usuario_password"];
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
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo usuario_nacimiento es requerido',
            ]
        ],
        'usuario_dui' => [
            'rules' => 'required|is_unique[usuario.usuario_dui]',
            'errors' => [
                'required' => 'El campo usuario_dui es requerido',
                'is_unique' => 'El campo usuario_dui debe de ser unico'
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

    protected function hashPassword(array $user)
    {
        $user['data']['usuario_password'] = password_hash($user['data']['usuario_password'], PASSWORD_DEFAULT);

        return $user;
    }
}
