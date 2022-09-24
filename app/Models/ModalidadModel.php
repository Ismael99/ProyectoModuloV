<?php

namespace App\Models;

use App\Entities\ModalidadEntity;
use CodeIgniter\Model;


class ModalidadModel extends Model
{
    protected $table = "modalidad";
    protected $primaryKey = 'modalidad_id';
    protected $returnType = ModalidadEntity::class;
    protected $createdField  = 'modalidad_created_at';
    protected $allowedFields = ["modalidad_nombre"];
    public $rules = [
        'modalidad_nombre' => [
            'rules' => 'required|is_unique[modalidad.modalidad_nombre]',
            'errors' => [
                'required' => 'El campo modalidad_nombre es requerido',
                'is_unique' => 'El campo modalidad_nombre debe de ser único'
            ]
        ],

    ];
}