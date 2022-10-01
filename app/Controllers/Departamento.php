<?php

namespace App\Controllers;

use App\Entities\DepartamentoEntity;
use App\Models\DepartamentoModel;

use CodeIgniter\API\ResponseTrait;

class Departamento extends BaseController
{

    use ResponseTrait;

    public function create()
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

            $departamento = $departamentoModel->where('departamento.departamento_nombre', $departamento['departamento_nombre'])->first();

            $response = [
                'statusCode' => 201,
                'data' => $departamento
            ];
            return $this->respond($response);
        }
    }

    public function get()
    {
        $departamentoModel = new DepartamentoModel();
        $departamentoData = $departamentoModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $departamentoData
        ];
        return $this->respond($response);
    }

    public function update($departamento_id)
    {
        $departamentoModel = new DepartamentoModel();
        $departamento = new DepartamentoEntity();
        $departamento = $this->request->getVar();

        $departamento_id_num = (int) $departamento_id;

        $departamentoToUpdate = $departamentoModel->where("departamento.departamento_id", $departamento_id_num)->first();

        if ($departamento_id_num <= 0 || $departamentoToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }
 
        $dataPrev = [
            "departamento_nombre" => $departamentoToUpdate->departamento_nombre,
            "departamento_descripcion" => $departamentoToUpdate->departamento_descripcion,
        ];

        $data = array_merge($dataPrev, $departamento);

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $validation = \Config\Services::validation();
        $validation->setRules($departamentoModel->rulesUpdate);
        
        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            if(count($data) > 0){
                $departamentoModel->update($departamento_id_num, $data);
            }
            $departamentoUpdated = $departamentoModel->where("departamento.departamento_id", $departamento_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $departamentoUpdated
            ];
            return $this->respond($response);
        }
    } 

    public function delete($departamento_id)
    {
       $departamento_id_num = (int) $departamento_id;
       $departamentoModel = new DepartamentoModel();
       
       if ($departamento_id_num <= 0 || $departamentoModel->where("departamento.departamento_id", $departamento_id_num)->first() == null ) {
           $response = [
               'statusCode' => 400,
               'errors' => 'El id no es valido'
           ];
           return $this->respond($response, 400);

       }
       else {
        $departamentoModel->delete($departamento_id_num);
        $response = [
            'statusCode' => 200,
            'msg' => 'departamento eliminado'
        ];
        return $this->respond($response, 200);
    }
       
    }
}
