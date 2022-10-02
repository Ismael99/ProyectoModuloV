<?php

namespace App\Controllers;

use App\Entities\CapacitacionFotoEntity;
use App\Models\CapacitacionFotoModel;
use App\Models\UsuarioModel;
use App\Models\CapacitacionModel;

use CodeIgniter\API\ResponseTrait;

class CapacitacionFoto extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $capacitacionFotoModel = new CapacitacionFotoModel();
        $capacitacionModel = new CapacitacionModel();
        $capacitacionFoto = new CapacitacionFotoEntity();
        $capacitacionFoto = $this->request->getVar();
        if($capacitacionModel->where("capacitacion.capacitacion_id", $capacitacionFoto["capacitacion_id"])->first() == null){
            $response = [
                'statusCode' => 400,
                'errors' => 'El capacitacion_id no es válido'
            ];
            return $this->respond($response, 400);
        }
        if (!$this->validate($capacitacionFotoModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response, 400);
        } else {
            $capacitacionFotoModel->save($capacitacionFoto);
            $response = [
                'statusCode' => 201,
                'data' => $capacitacionFoto
            ];
            return $this->respond($response, 201);
        }
    }

    public function get()
    {
        $capacitacionFotoModel = new CapacitacionFotoModel();
        $capacitacionFotoesData = $capacitacionFotoModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $capacitacionFotoesData
        ];
        return $this->respond($response, 200);
    }

     public function update($capacitacion_foto_id)
    {
        $capacitacionFotoModel = new CapacitacionFotoModel();
        $capacitacionFoto = new CapacitacionFotoEntity();
        $capacitacionFoto = $this->request->getVar();

        $capacitacion_foto_id_num = (int) $capacitacion_foto_id;

        $capacitacionFotoToUpdate = $capacitacionFotoModel->where("capacitacion_foto.capacitacion_foto_id", $capacitacion_foto_id_num)->first();

        if ($capacitacion_foto_id_num <= 0 || $capacitacionFotoToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El capacitacion_foto_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $dataPrev = [
            "capacitacion_foto_url" => $capacitacionFotoToUpdate->capacitacion_foto_nombre,
            "capacitacion_id" => $capacitacionFotoToUpdate->capacitacion_id
        ];

        $data = array_merge($dataPrev, $capacitacionFoto);

        //Para llaves foraneas
        $capacitacionModel = new CapacitacionModel();
        if($capacitacionModel->where("capacitacion.capacitacion_id", (int) $data["capacitacion_id"])->first() ==null ){
            $response = [
                'statusCode' => 400,
                'errors' => 'capacitacion_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $rules = $capacitacionFotoModel->rulesUpdate;
        if($data["capacitacion_id"] != null){
            $rules = array_merge($rules, [  
                'capacitacion_id' => [
                    'rules' => 'integer',
                    'errors' => [
                        'integer' => 'El campo capacitacion_id es un numero entero',
                    ]
                ],
            ] );
        }
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        
        if (!$validation->run($data) && count($rules) > 0) {
            $errors = $validation->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            if(count($data) > 0){
                $capacitacionFotoModel->update($capacitacionFoto_id_num, $data);
            }
            $capacitacionFotoUpdated = $capacitacionFotoModel->where("capacitacion_foto.capacitacion_foto_id", $capacitacion_foto_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $capacitacionFotoUpdated
            ];
            return $this->respond($response);
        }
    }

    public function delete($capacitacion_foto_id)
    {
        $capacitacion_foto_id_num = (int) $capacitacion_foto_id;
        $capacitacionFotoModel = new CapacitacionFotoModel();
        if ($capacitacion_foto_id_num <= 0 || $capacitacionFotoModel->where("capacitacion_foto.capacitacion_foto_id", $capacitacion_foto_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            $capacitacionFotoModel->delete($capacitacion_foto_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'CapacitacionFoto eliminada'
            ];
            return $this->respond($response, 200);
        }
        
    }
}
