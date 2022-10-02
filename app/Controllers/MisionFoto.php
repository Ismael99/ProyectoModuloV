<?php

namespace App\Controllers;

use App\Entities\MisionFotoEntity;
use App\Models\MisionFotoModel;
use App\Models\UsuarioModel;
use App\Models\MisionModel;

use CodeIgniter\API\ResponseTrait;

class MisionFoto extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $misionFotoModel = new MisionFotoModel();
        $misionModel = new MisionModel();
        $misionFoto = new MisionFotoEntity();
        $misionFoto = $this->request->getVar();
        if($misionModel->where("mision.mision_id", $misionFoto["mision_id"])->first() == null){
            $response = [
                'statusCode' => 400,
                'errors' => 'El mision_id no es válido'
            ];
            return $this->respond($response, 400);
        }
        if (!$this->validate($misionFotoModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response, 400);
        } else {
            $misionFotoModel->save($misionFoto);
            $response = [
                'statusCode' => 201,
                'data' => $misionFoto
            ];
            return $this->respond($response, 201);
        }
    }

    public function get()
    {
        $misionFotoModel = new MisionFotoModel();
        $misionFotoesData = $misionFotoModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $misionFotoesData
        ];
        return $this->respond($response, 200);
    }

     public function update($mision_foto_id)
    {
        $misionFotoModel = new MisionFotoModel();
        $misionFoto = new MisionFotoEntity();
        $misionFoto = $this->request->getVar();

        $mision_foto_id_num = (int) $mision_foto_id;

        $misionFotoToUpdate = $misionFotoModel->where("mision_foto.mision_foto_id", $mision_foto_id_num)->first();

        if ($mision_foto_id_num <= 0 || $misionFotoToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El mision_foto_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $dataPrev = [
            "mision_foto_url" => $misionFotoToUpdate->mision_foto_nombre,
            "mision_id" => $misionFotoToUpdate->mision_id
        ];

        $data = array_merge($dataPrev, $misionFoto);

        //Para llaves foraneas
        $misionModel = new MisionModel();
        if($misionModel->where("mision.mision_id", (int) $data["mision_id"])->first() ==null ){
            $response = [
                'statusCode' => 400,
                'errors' => 'mision_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $rules = $misionFotoModel->rulesUpdate;
        if($data["mision_id"] != null){
            $rules = array_merge($rules, [  
                'mision_id' => [
                    'rules' => 'integer',
                    'errors' => [
                        'integer' => 'El campo mision_id es un numero entero',
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
                $misionFotoModel->update($misionFoto_id_num, $data);
            }
            $misionFotoUpdated = $misionFotoModel->where("mision_foto.mision_foto_id", $mision_foto_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $misionFotoUpdated
            ];
            return $this->respond($response);
        }
    }

    public function delete($mision_foto_id)
    {
        $mision_foto_id_num = (int) $mision_foto_id;
        $misionFotoModel = new MisionFotoModel();
        if ($mision_foto_id_num <= 0 || $misionFotoModel->where("mision_foto.mision_foto_id", $mision_foto_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            $misionFotoModel->delete($mision_foto_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'MisionFoto eliminada'
            ];
            return $this->respond($response, 200);
        }
        
    }
}
