<?php

namespace App\Validation;

use App\Models\UsuarioModel;

class UserRules
{
    public function validateUser(string $str, string $fields, array $data)
    {
        $model = new UsuarioModel();

        $user = $model->where('usuario_username', $data['usuario_username'])->first();

        if (!$user) {
            return false;
        }

        return password_verify($data['usuario_password'], $user->usuario_password);
    }
}
