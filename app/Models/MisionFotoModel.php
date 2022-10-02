<?php

namespace App\Models;

use App\Entities\MisionFotoEntity;
use CodeIgniter\Model;

class MisionFotoModel extends Model
{
    protected $table = "mision_foto";
    protected $primaryKey = 'mision_foto_id';
    protected $returnType = MisionFotoEntity::class;
    protected $createdField  = 'mision_foto_created_at';
    protected $allowedFields = ["mision_foto_url", 
                                "mision_id"];
    public $rules = [
        'mision_foto_url' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'El campo mision_foto_url es requerido',
            ]
        ],

        'mision_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo mision es requerido',
            ]
        ],
        
    ];
    
    public $rulesUpdate = [];

}
