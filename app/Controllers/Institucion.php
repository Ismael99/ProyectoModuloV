<?php

namespace App\Controllers;

use App\Entities\InstitucionEntity;
use App\Models\InstitucionModel;

use CodeIgniter\API\ResponseTrait;

class Institucion extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $institucionModel = new InstitucionModel();
        $institucion = new InstitucionEntity();
        $institucion = $this->request->getVar();

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

    /* public function update($institucion_id)
    {
        $institucionModel = new InstitucionModel();
        $institucion = new InstitucionEntity();
        $institucion = $this->request->getVar();

        $institucion_id_num = (int) $institucion_id;

        if ($institucion_id_num <= 0 || $institucionModel->where("institucion.id", $institucion_id_num)->first() == null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }

        $institucionToUpdate = $institucionModel->where("institucion.id", $institucion_id_num)->first();
        // $data = [...$institucion, ...$institucionToUpdate];
        
        $dataPrev = [
            "nombre" => $institucionToUpdate->nombre,
            "descripcion" => $institucionToUpdate->descripcion
        ];
        
        
        $data = array_merge($dataPrev, $institucion);
        return $this->respond($institucion);

        if (!$this->validate($institucionModel->rules)) {
            $errors = $this->validator->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $institucionModel->update($institucion_id_num, $data);
            $response = [
                'statusCode' => 201,
                'data' => $institucion
            ];
            return $this->respond($response);
        }
    } */

    public function delete($institucion_id)
    {
        $institucion_id_num = (int) $institucion_id;
        $institucionModel = new InstitucionModel();
        $institucionesData = $institucionModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $institucionesData
        ];
        if ($institucion_id_num <= 0 || $institucionModel->where("institucion.institucion_id", $institucion_id_num)->first() == null ) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        } else {
            // $institucionModel->delete($institucion_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Institucion eliminada'
            ];
            return $this->respond($response);
        }
        return $this->respond($response);
    }
}
