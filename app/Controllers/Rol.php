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
            return $this->respond($response, 400);
        } else {
            $rolModel->save($rol);
            $response = [
                'statusCode' => 201,
                'data' => $rol
            ];
            return $this->respond($response, 201);
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

    public function update($rol_id)
    {
        $rolModel = new RolModel();
        $rol = new RolEntity();
        $rol = $this->request->getVar();

        $rol_id_num = (int) $rol_id;

        $rolToUpdate = $rolModel->where("rol.rol_id", $rol_id_num)->first();

        if ($rol_id_num <= 0 || $rolToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }
 
        $dataPrev = [
            "rol_nombre" => $rolToUpdate->rol_nombre,
            "rol_descripcion" => $rolToUpdate->rol_descripcion,
        ];

        $data = array_merge($dataPrev, $rol);

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $validation = \Config\Services::validation();
        $validation->setRules($rolModel->rulesUpdate);
        
        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            if(count($data) > 0){
                $rolModel->update($rol_id_num, $data);
            }
            $rolUpdated = $rolModel->where("rol.rol_id", $rol_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $rolUpdated
            ];
            return $this->respond($response);
        }
    } 

    public function delete($rol_id)
    {
        $rol_id_num = (int) $rol_id;
        $rolModel = new rolModel();
        if ($rol_id_num <= 0 || $rolModel->where("rol.rol_id", $rol_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            $rolModel->delete($rol_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Modalidad eliminada'
            ];
            return $this->respond($response, 200);
        }
        
    }
}
