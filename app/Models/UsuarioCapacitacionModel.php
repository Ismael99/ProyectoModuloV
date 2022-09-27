<?php

namespace App\Models;

use App\Entities\UsuarioCapacitacionEntity;
use CodeIgniter\Model;

class UsuarioCapacitacionModel extends Model
{
    protected $table = 'usuario_capacitacion';
    protected $primaryKey = 'usuario_capacitacion_id';
    protected $returnType = UsuarioCapacitacionEntity::class;
    protected $createdField  = 'usuario_capacitacion_created_at';
    protected $allowedFields = ['usuario_id', 'capacitacion_id', 'usuario_capacitacion_inscripcion', 'usuario_capacitacion_diploma', 'capacitacion_id', 'capacitacion_estatus'];

    protected $validationRules = [
        'usuario_id' => 'required|integer',
        'capacitacion_id' => 'required|integer',
        'usuario_capacitacion_comentarios' => 'permit_empty',
        'usuario_capacitacion_diploma' => 'permit_empty|valid_url_strict',
        'usuario_capacitacion_inscripcion' => 'required|valid_url_strict',
        'capacitacion_estatus' => 'required|in_list[en proceso,reprobada,finalizada con diploma]'
    ];
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'El usuario_id es requerido',
            'integer' => 'El usuario_id debe ser un id valido',
        ],
        'capacitacion_id' => [
            'required' => 'El usuario_id es requerido',
            'integer' => 'El usuario_id debe ser un id valido',
        ],
        'usuario_capacitacion_inscripcion' => [
            'valid_url_strict' => 'Debe ser una url valida',
            'required' => 'Es necesaria la inscripcion'
        ],
        'usuario_capacitacion_diploma' => [
            'valid_url_strict' => 'Debe ser una url valida'
        ],
        'capacitacion_estatus' => [
            'required' => 'El campo de estatus es requerido',
            'in_list' => 'Estatus debe ser uno de los siguientes valores: en proceso, reprobada, finalizada con diploma'
        ]
    ];
}
