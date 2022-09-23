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
            'rules' => 'required|is_unique[departamento.departamento_nombre]|max_length[25]',
            'errors' => [
                'required' => 'El campo departamento_nombre es requerido',
                'is_unique' => 'El campo departamento_nombre debe de ser único',
                'max_length' => 'El campo departamento_nombre debe de ser menos de 25 caracteres',
            ]
        ],
    ];
}
