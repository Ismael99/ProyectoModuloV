<?php

namespace App\Controllers;

use App\Entities\RolEntity;
use App\Models\RolModel;

use CodeIgniter\API\ResponseTrait;

class Rol extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $rolModel = new RolModel();
        $rol = new RolEntity();
        $rol = $this->request->getVar();

        if (!$this->validate($rolModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $rolModel->save($rol);
            $response = [
                'statusCode' => 201,
                'data' => $rol
            ];
            return $this->respond($response);
        }
    }

    public function get()
    {
        $rolModel = new RolModel();
        $rolesData = $rolModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $rolesData
        ];
        return $this->respond($response);
    }

    /* public function update($rol_id)
    {
        $rolModel = new RolModel();
        $rol = new RolEntity();
        $rol = $this->request->getVar();

        $rol_id_num = (int) $rol_id;

        if ($rol_id_num <= 0 || !$rolModel->find($rol_id_num)) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }

        $rolToUpdate = $rolModel->find($rol_id_num);
        // return $this->respond($rolToUpdate);

        if (!$this->validate($rolModel->rules)) {
            $errors = $this->validator->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $rolModel->update($rol_id_num, $rol);
            $response = [
                'statusCode' => 201,
                'data' => $rol
            ];
            return $this->respond($response);
        }
    } */

    public function delete($rol_id)
    {
        $rol_id_num = (int) $rol_id;
        $rolModel = new RolModel();
        $rolesData = $rolModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $rolesData
        ];
        if ($rol_id_num <= 0) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        } else {
            $rolModel->delete($rol_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Rol eliminado'
            ];
            return $this->respond($response);
        }
        return $this->respond($response);
    }
}
