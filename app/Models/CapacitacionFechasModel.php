<?php

namespace App\Models;

use App\Entities\CapacitacionFechasEntity;
use CodeIgniter\Model;

class CapacitacionFechasModel extends Model
{
    protected $table = "capacitacion_fechas";
    protected $primaryKey = 'capacitacion_fechas_id';
    protected $returnType = CapacitacionFechasEntity::class;
    protected $createdField  = 'capacitacion_fechas_created_at';
    protected $allowedFields = [
        "capacitacion_fechas_fecha",
        "capacitacion_id"
    ];
    public $rules = [
        'capacitacion_fechas_fecha' => [
            'rules' => 'required|valid_date[Y-m-d]',
            'errors' => [
                'required' => 'El campo capacitacion_fechas_fecha es requerido',
                'valid_date' => 'Formato invalido para mision_fechas_fecha: Y-m-d',
            ]
        ],

        'capacitacion_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo capacitacion es requerido',
            ]
        ],

    ];
}
