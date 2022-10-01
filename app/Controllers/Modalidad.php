<?php

namespace App\Controllers;

use App\Entities\ModalidadEntity;
use App\Models\ModalidadModel;

use CodeIgniter\API\ResponseTrait;

class Modalidad extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $modalidadModel = new ModalidadModel();
        $modalidad = new ModalidadEntity();
        $modalidad = $this->request->getVar();
        if (!$this->validate($modalidadModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response, 400);
        } else {
            $modalidadModel->save($modalidad);
            $response = [
                'statusCode' => 201,
                'data' => $modalidad
            ];
            return $this->respond($response, 201);
        }
    }

    public function get()
    {
        $modalidadModel = new ModalidadModel();
        $modalidadesData = $modalidadModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $modalidadesData
        ];
        return $this->respond($response, 200);
    }

     public function update($modalidad_id)
    {
        $modalidadModel = new ModalidadModel();
        $modalidad = new ModalidadEntity();
        $modalidad = $this->request->getVar();

        $modalidad_id_num = (int) $modalidad_id;

        $modalidadToUpdate = $modalidadModel->where("modalidad.modalidad_id", $modalidad_id_num)->first();

        if ($modalidad_id_num <= 0 || $modalidadToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }
 
        $dataPrev = [
            "modalidad_nombre" => $modalidadToUpdate->modalidad_nombre,
        ];

        $data = array_merge($dataPrev, $modalidad);

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $validation = \Config\Services::validation();
        $validation->setRules($modalidadModel->rulesUpdate);
        
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
                $modalidadModel->update($modalidad_id_num, $data);
            }
            $modalidadUpdated = $modalidadModel->where("modalidad.modalidad_id", $modalidad_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $modalidadUpdated
            ];
            return $this->respond($response);
        }
    } 

    public function delete($modalidad_id)
    {
        $modalidad_id_num = (int) $modalidad_id;
        $modalidadModel = new ModalidadModel();
        if ($modalidad_id_num <= 0 || $modalidadModel->where("modalidad.modalidad_id", $modalidad_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            $modalidadModel->delete($modalidad_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Modalidad eliminada'
            ];
            return $this->respond($response, 200);
        }
    }
}