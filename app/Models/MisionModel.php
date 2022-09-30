<?php

namespace App\Models;

use App\Entities\MisionEntity;
use CodeIgniter\Model;

class MisionModel extends Model
{
    protected $table = "mision";
    protected $primaryKey = 'mision_id';
    protected $returnType = MisionEntity::class;
    protected $createdField  = 'mision_created_at';
    protected $updatedField  = 'mision_updated_at';
    protected $allowedFields = [
        "mision_nombre",
        "mision_descripcion",
        "mision_participantes",
        "institucion_id"
    ];
    public $rules = [
        'mision_nombre' => [
            'rules' => 'required|is_unique[mision.mision_nombre]',
            'errors' => [
                'required' => 'El campo mision_nombre es requerido',
                'is_unique' => 'El campo mision_nombre debe de ser único'
            ]
        ],
        'mision_descripcion' => [
            'rules' => 'permit_empty',
        ],
        'mision_participantes' => [
            'rules' => 'permit_empty',
        ],
        'institucion_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo institucion es requerido',
                'integer' => 'EL campo debe ser un numero entero'
            ]
        ],

    ];
}
