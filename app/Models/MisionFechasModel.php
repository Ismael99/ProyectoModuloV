<?php

namespace App\Models;

use App\Entities\MisionFechasEntity;
use CodeIgniter\Model;

class MisionFechasModel extends Model
{
    protected $table = "mision_fechas";
    protected $returnType = MisionFechasEntity::class;
    protected $createdField  = 'mision_fechas_created_at';
    protected $allowedFields = [
        "mision_fechas_fecha",
        "mision_id"
    ];
    protected $primaryKey = 'mision_fechas_id';

    public $rules = [
        'mision_fechas_fecha' => [
            'rules' => 'required|valid_date[Y-m-d]',
            'errors' => [
                'required' => 'El campo mision_fechas_fecha es requerido',
                'valid_date' => 'Formato invalido para mision_fechas_fecha: Y-m-d',
            ]
        ],

        'mision_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo mision_id es requerido',
                'integer' => 'El campo mision_id debe ser un numero entero',
            ]
        ],

    ];
}
