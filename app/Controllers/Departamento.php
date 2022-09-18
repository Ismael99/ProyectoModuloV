<?php

namespace App\Controllers;

use App\Entities\DepartamentoEntity;
use App\Models\DepartamentoModel;

use CodeIgniter\API\ResponseTrait;

class Departamento extends BaseController
{

    use ResponseTrait;

    public function createAction()
    {
        $departamentoModel = new DepartamentoModel();
        $departamento = new DepartamentoEntity();
        $departamento = $this->request->getVar();

        if (!$this->validate($departamentoModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $departamentoModel->save($departamento);
            $response = [
                'statusCode' => 201,
                'data' => $departamento
            ];
            return $this->respond($response);
        }
    }

    public function getAction()
    {
        $departamentoModel = new DepartamentoModel();
        $departamentoData = $departamentoModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $departamentoData
        ];
        return $this->respond($response);
    }

  /*  public function updateAction($departamento_id)
    {
        $departamentoModel = new DepartamentoModel();
        $departamento = new DepartamentoEntity();
        $departamento = $this->request->getVar();

        $departamento_id_num = (int) $departamento_id;

        if ($departamento_id_num <= 0 || !$departamentoModel->find($departamento_id_num)) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }

        $rolToUpdate = $departamentoModel->find($departamento_id_num);
        // return $this->respond($rolToUpdate);

        if (!$this->validate($departamentoModel->rules)) {
            $errors = $this->validator->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $departamentoModel->update($rol_id_num, $departamento);
            $response = [
                'statusCode' => 201,
                'data' => $departamento
            ];
            return $this->respond($response);
        }
    } */

    public function deleteAction($departamento_id)
    {
        $departamento_id_num = (int) $departamento_id;
        $departamentoModel = new DepartamentoModel();
        $departamentoData = $departamentoModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $departamentoData
        ];
        if ($departamento_id_num <= 0) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }else{
            $departamentoModel->delete($departamento_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Departamento eliminado'
            ];
            return $this->respond($response);
        }
        return $this->respond($response);
    }
}
