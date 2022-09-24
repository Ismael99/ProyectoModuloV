<?php

namespace App\Models;

use App\Entities\CapacitacionFotoEntity;
use CodeIgniter\Model;

class CapacitacionFotoModel extends Model
{
    protected $table = "capacitacion_foto";
    protected $primaryKey = 'capacitacion_foto_id';
    protected $returnType = CapacitacionFotoEntity::class;
    protected $createdField  = 'capacitacion_foto_created_at';
    protected $allowedFields = ["capacitacion_foto_url", 
                                "capacitacion_id"];
    public $rules = [
        'capacitacion_foto_url' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo capacitacion_foto_url es requerido',
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
