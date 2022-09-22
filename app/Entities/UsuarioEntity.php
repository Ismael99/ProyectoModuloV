<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UsuarioEntity extends Entity
{
    protected $datamap = [
        // property_name => db_column_name
        'primer_nombre' => 'nombre',
        'user_id' => 'id'
    ];
}
