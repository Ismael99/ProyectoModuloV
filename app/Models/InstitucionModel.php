<?php

namespace App\Models;

use App\Entities\InstitucionEntity;
use CodeIgniter\Model;

class InstitucionModel extends Model
{
    protected $table = "institucion";
    protected $primaryKey = 'institucion_id';
    protected $returnType = InstitucionEntity::class;
    protected $createdField  = 'institucion_created_by';
    protected $updatedField  = 'institucion_updated_at';
    protected $allowedFields = ["institucion_nombre", "institucion_created_by"];
    public $rules = [
        'institucion_nombre' => [
            'rules' => 'required|is_unique[institucion.institucion_nombre]',
            'errors' => [
                'required' => 'El campo institucion_nombre es requerido',
                'is_unique' => 'El campo institucion_nombre debe de ser único'
            ]
        ],
        
    ];
    public $rulesUpdate = [
        'institucion_nombre' => [
            'rules' => 'is_unique[institucion.institucion_nombre]',
            'errors' => [
                'is_unique' => 'El campo institucion_nombre debe de ser único'
            ]
        ],
        
    ];

}
