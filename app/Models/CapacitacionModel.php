<?php

namespace App\Models;

use App\Entities\CapacitacionEntity;
use CodeIgniter\Model;

class CapacitacionModel extends Model
{
    protected $table = "capacitacion";
    protected $primaryKey = 'capacitacion_id';
    protected $returnType = CapacitacionEntity::class;
    protected $createdField  = 'capacitacion_created_at';
    protected $updatedField  = 'capacitacion_updated_at';
    protected $allowedFields = [
        "capacitacion_nombre",
        "institucion_id",
        "modalidad_id"
    ];

    protected $validationRules = [
        'capacitacion_nombre' => 'required|is_unique[capacitacion.capacitacion_nombre]',
        'institucion_id' => 'required|integer',
        'modalidad_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'capacitacion_nombre' => [
            'required' => 'El campo capacitacion_nombre es requerido',
            'is_unique' => 'El campo capacitacion_nombre debe de ser único'
        ],
        'institucion_id' => [
            'required' => 'El campo institucion_id es requerido',
            'integer' => 'El campo institucion_id debe ser un numero entero'
        ],
        'modalidad_id' => [
            'required' => 'El campo modalidad_id es requerido',
            'integer' => 'El campo modalidad_id debe ser un numero entero'
        ]
    ];
}
