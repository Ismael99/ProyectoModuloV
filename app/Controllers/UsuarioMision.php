<?php

namespace App\Controllers;

use App\Models\MisionModel;
use App\Models\UsuarioMisionModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class UsuarioMision extends BaseController
{
    use ResponseTrait;

    protected UsuarioMisionModel $model;
    protected MisionModel $misionModel;
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->model = new UsuarioMisionModel();
        $this->misionModel = new MisionModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index($model = null, $id = null)
    {
        $query = $this
            ->model
            ->join('usuario', 'usuario_mision.usuario_id = usuario.usuario_id', 'left')
            ->join('mision', 'usuario_mision.mision_id = mision.mision_id', 'left');

        if ($model) {
            $where = '';
            if ($model === 'usuario') {
                $where = ['usuario_mision.usuario_id' => $id];
            } else {
                $where = ['usuario_mision.mision_id' => $id];
            }

            $query = $query->where($where);
        }

        $usuariosMisiones  = $query->findAll();

        $usuariosMisiones = array_map(
            function ($usuarioMision) {
                unset($usuarioMision->usuario_password);
                return $usuarioMision;
            },
            $usuariosMisiones
        );

        $response = [
            'data' => $usuariosMisiones,
            'statusCode' => 200,
        ];

        return $this->respond($response, 200);
    }

    public function create()
    {
        $rules = $this->model->getValidationRules();
        $messages = $this->model->getValidationMessages();
        if (!$this->validate($rules, $messages)) {
            $response = [
                'errors' => $this->validator->getErrors(),
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $input = $this->request->getPost();

        $usuario_id = $input['usuario_id'];
        $mision_id = $input['mision_id'];


        $entityValidationResults = array(
            'usuario_id' => $this->isInvalidEntityId($this->usuarioModel, $usuario_id),
            'mision_id' => $this->isInvalidEntityId($this->misionModel, $mision_id)
        );

        $entityErrors = array_filter($entityValidationResults, function ($error) {
            return !is_null($error);
        });

        if (count($entityErrors) > 0) {
            $response = [
                'errors' => (object) $entityErrors,
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        if (!is_null($this->findUsuarioMision($usuario_id, $mision_id))) {
            $response = [
                'message' => 'Este usuario ya esta asociado con esta mision',
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $this->model->save($input);

        $usuarioMision = $this->findUsuarioMision($usuario_id, $mision_id);

        $response = [
            'data' => $usuarioMision,
            'statusCode' => 201
        ];

        return $this->respond($response, 201);
    }

    public function delete(int $usuario_id, int $mision_id)
    {
        $entityValidationResults = array(
            'usuario_id' => $this->isInvalidEntityId($this->usuarioModel, $usuario_id),
            'mision_id' => $this->isInvalidEntityId($this->misionModel, $mision_id)
        );

        $entityErrors = array_filter($entityValidationResults, function ($error) {
            return !is_null($error);
        });

        if (count($entityErrors) > 0) {
            $response = [
                'errors' => (object) $entityErrors,
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $usuario_mision = $this->findUsuarioMision($usuario_id, $mision_id);

        if (is_null($usuario_mision)) {
            $response = [
                'message' => 'Esta mision no esta asociada a este usuario',
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $response = $this
            ->model
            ->where('usuario_mision.mision_id', $mision_id)
            ->where('usuario_mision.usuario_id', $usuario_id)
            ->delete();

        return $this->respond($response, 200);
    }

    private function findUsuarioMision(int $usuario_id, int $mision_id)
    {
        $usuarioMision = $this
            ->model
            ->where('usuario_mision.usuario_id', $usuario_id)
            ->where('usuario_mision.mision_id', $mision_id)
            ->first();

        return $usuarioMision;
    }

    private function isInvalidEntityId(UsuarioModel|MisionModel $model, int $id, string $message = 'Id invalido')
    {
        $entity = $model->find($id);

        if (is_null($entity)) {
            return $message;
        }

        return null;
    }
}
