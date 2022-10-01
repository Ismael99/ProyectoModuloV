<?php

namespace App\Controllers;

use App\Entities\InstitucionEntity;
use App\Models\InstitucionModel;
use App\Models\UsuarioModel;

use CodeIgniter\API\ResponseTrait;

class Institucion extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $institucionModel = new InstitucionModel();
        $institucion = new InstitucionEntity();
        $institucion = $this->request->getVar();
        //Add created by field
        $institucion["institucion_created_by"] = $this->request->user->usuario_id;
        if (!$this->validate($institucionModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response, 400);
        } else {
            $institucionModel->save($institucion);
            $response = [
                'statusCode' => 201,
                'data' => $institucion
            ];
            return $this->respond($response, 201);
        }
    }

    public function get()
    {
        $institucionModel = new InstitucionModel();
        $institucionesData = $institucionModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $institucionesData
        ];
        return $this->respond($response, 200);
    }

     public function update($institucion_id)
    {
        $institucionModel = new InstitucionModel();
        $institucion = new InstitucionEntity();
        $institucion = $this->request->getVar();

        $institucion_id_num = (int) $institucion_id;

        $institucionToUpdate = $institucionModel->where("institucion.institucion_id", $institucion_id_num)->first();

        if ($institucion_id_num <= 0 || $institucionToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El institucion_id no es válido'
            ];
            return $this->respond($response, 400);
        }
 
        $dataPrev = [
            "institucion_nombre" => $institucionToUpdate->institucion_nombre,
            "institucion_created_by" => $institucionToUpdate->institucion_created_by
        ];

        $data = array_merge($dataPrev, $institucion);

        //Para llaves foraneas
        $userModel = new UsuarioModel();
        if($userModel->where("usuario.usuario_id", (int) $data["institucion_created_by"])->first() ==null ){
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
        $validation->setRules($institucionModel->rulesUpdate);
        
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
                $institucionModel->update($institucion_id_num, $data);
            }
            $institucionUpdated = $institucionModel->where("institucion.institucion_id", $institucion_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $institucionUpdated
            ];
            return $this->respond($response);
        }
    }

    public function delete($institucion_id)
    {
        $institucion_id_num = (int) $institucion_id;
        $institucionModel = new InstitucionModel();
        if ($institucion_id_num <= 0 || $institucionModel->where("institucion.institucion_id", $institucion_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response, 400);
        } else {
            // $institucionModel->delete($institucion_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Institucion eliminada'
            ];
            return $this->respond($response, 200);
        }
        
    }
}
