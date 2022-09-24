<?php

namespace App\Models;

use App\Entities\MisionFechasEntity;
use CodeIgniter\Model;

class MisionFechasModel extends Model
{
    protected $table = "mision_fechas";
    protected $returnType = MisionFechasEntity::class;
    protected $createdField  = 'mision_fechas_created_at';
    protected $allowedFields = ["mision_fechas_fecha",  
                                "mision_id"];
    public $rules = [
        'mision_fechas_fecha' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo mision_fechas_fecha es requerido',
                
            ]
        ],

        'mision_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo mision es requerido',
            ]
        ],
        
    ];

}
