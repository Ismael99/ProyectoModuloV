<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Entities\DepartamentoEntity;

class DepartamentoModel extends Model
{
    protected $table = "departamento";
    protected $primaryKey = 'departamento_id';
    protected $returnType = DepartamentoEntity::class;
    protected $allowedFields = ["departamento_nombre", "departamento_descripcion"];
    public $rules = [
        'departamento_nombre' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo nombre es requerido',
            ]
        ],
    ];
}
