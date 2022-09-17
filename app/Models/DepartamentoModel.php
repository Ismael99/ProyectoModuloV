<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\DepartamentoEntity;

class DepartamentoModel extends Model
{
    protected $table = "departamento";
    protected $primaryKey = 'id';
    protected $returnType = DepartamentoEntity::class;
    protected $allowedFields = ["nombre", "descripcion"];
    public $rules = [
        'nombre' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo nombre es requirido',
            ]
        ],
    ];
}
