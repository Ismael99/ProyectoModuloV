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

     public function update($misionFoto_id)
    {
        $misionFotoModel = new MisionFotoModel();
        $misionFoto = new MisionFotoEntity();
        $misionFoto = $this->request->getVar();

        $misionFoto_id_num = (int) $misionFoto_id;

        $misionFotoToUpdate = $misionFotoModel->where("misionFoto.misionFoto_id", $misionFoto_id_num)->first();

        if ($misionFoto_id_num <= 0 || $misionFotoToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El misionFoto_id no es válido'
            ];
            return $this->respond($response, 400);
        }
 
        $dataPrev = [
            "misionFoto_nombre" => $misionFotoToUpdate->misionFoto_nombre,
            "misionFoto_created_by" => $misionFotoToUpdate->misionFoto_created_by
        ];

        $data = array_merge($dataPrev, $misionFoto);

        //Para llaves foraneas
        $userModel = new UsuarioModel();
        if($userModel->where("usuario.usuario_id", (int) $data["misionFoto_created_by"])->first() ==null ){
            $response = [
                'statusCode' => 400,
                'errors' => 'El usuario_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $validation = \Config\Services::validation();
        $validation->setRules($misionFotoModel->rulesUpdate);
        
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
                $misionFotoModel->update($misionFoto_id_num, $data);
            }
            $misionFotoUpdated = $misionFotoModel->where("misionFoto.misionFoto_id", $misionFoto_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $misionFotoUpdated
            ];
            return $this->respond($response);
        }
    }

    public function delete($misionFoto_id)
    {
        $misionFoto_id_num = (int) $misionFoto_id;
        $misionFotoModel = new MisionFotoModel();
        if ($misionFoto_id_num <= 0 || $misionFotoModel->where("misionFoto.misionFoto_id", $misionFoto_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            // $misionFotoModel->delete($misionFoto_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'MisionFoto eliminada'
            ];
            return $this->respond($response, 200);
        }
        
    }
}
