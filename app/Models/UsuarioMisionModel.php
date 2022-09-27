<?php

namespace App\Models;

use App\Entities\UsuarioMisionEntity;
use CodeIgniter\Model;

class UsuarioMisionModel extends Model
{
    protected $table = 'usuario_mision';
    protected $primaryKey = 'usuario_mision_id';
    protected $returnType = UsuarioMisionEntity::class;
    protected $createdField  = 'usuario_mision_created_at';
    protected $allowedFields = ['usuario_id', 'mision_id', 'usuario_mision_comentarios'];

    protected $validationRules = [
        'usuario_id' => 'required|integer',
        'mision_id' => 'required|integer',
        'usuario_mision_comentarios' => 'permit_empty|min_length[10]'
    ];
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'El usuario_id es requerido',
            'integer' => 'El usuario_id debe ser un id valido',
        ],
        'mision_id' => [
            'required' => 'El usuario_id es requerido',
            'integer' => 'El usuario_id debe ser un id valido',
        ],
        'usuario_mision_comentarios' => [
            'min_length' => 'El usuario_mision_comentarios debe ser al menos 10 caracteres'
        ]
    ];
}
