<?php

namespace App\Controllers;

use App\Entities\DepartamentoEntity;
use App\Entities\UsuarioEntity;
use App\Models\DepartamentoModel;
use App\Models\UsuarioModel;
use App\Models\RolModel;
use CodeIgniter\API\ResponseTrait;

class Usuario extends BaseController
{

    use ResponseTrait;

    public function createAction()
    {
        $usuarioModel = new UsuarioModel();
        $rolModel = new RolModel();
        $departamentoModel = new DepartamentoModel();

        $usuario = new UsuarioEntity();
        $usuario = $this->request->getVar();

        if($rolModel->where("id", $usuario->rol_id)){
            $response = [
                'statusCode' => 400,
                'errors' => 'Rol no valido'
            ];
            return $this->respond($response);
        }

        if($departamentoModel->where("id",$usuario->departamento_id)){
            $response = [
                'statusCode' => 400,
                'errors' => 'Departamento no valido'
            ];
            return $this->respond($response);
        }

        if (!$this->validate($usuarioModel->rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            $response = $usuarioModel->save($usuario);
            $response = [
                'statusCode' => 201,
                'data' => $usuario
            ];
            return $this->respond($response);
        }
    }

    public function getAction()
    {
        $usuarioModel = new UsuarioModel();
        $usuarioData = $usuarioModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $usuarioData
        ];
        return $this->respond($response);
    }

    public function updateAction($departamento_id)
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
    }

    public function deleteAction($usuario_id)
    {
        $usuario_id_num = (int) $usuario_id;
        $usuarioModel = new UsuarioModel();
        $usuarioData = $usuarioModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $usuarioData
        ];
        if ($usuario_id_num <= 0) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El id no es válido'
            ];
            return $this->respond($response);
        }else{
            $usuarioModel->delete($usuario_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Usuario eliminado'
            ];
            return $this->respond($response);
        }
        return $this->respond($response);
    }
}
