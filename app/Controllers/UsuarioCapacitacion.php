<?php

namespace App\Controllers;

use App\Models\CapacitacionModel;
use App\Models\UsuarioCapacitacionModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class UsuarioCapacitacion extends BaseController
{
    use ResponseTrait;

    protected UsuarioCapacitacionModel $model;
    protected CapacitacionModel $capacitacionModel;
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->model = new UsuarioCapacitacionModel();
        $this->capacitacionModel = new CapacitacionModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index($model = null, $id = null)
    {
        $query = $this
            ->model
            ->join('usuario', 'usuario_capacitacion.usuario_id = usuario.usuario_id', 'left')
            ->join('capacitacion', 'usuario_capacitacion.capacitacion_id = capacitacion.capacitacion_id', 'left');

        if ($model) {
            $where = '';
            if ($model === 'usuario') {
                $where = ['usuario_capacitacion.usuario_id' => $id];
            } else {
                $where = ['usuario_capacitacion.capacitacion_id' => $id];
            }

            $query = $query->where($where);
        }

        $usuariosCapacitaciones  = $query->findAll();

        $usuariosCapacitaciones = array_map(
            function ($usuarioCapacitacion) {
                unset($usuarioCapacitacion->usuario_password);
                return $usuarioCapacitacion;
            },
            $usuariosCapacitaciones
        );

        $response = [
            'data' => $usuariosCapacitaciones,
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
        $capacitacion_id = $input['capacitacion_id'];


        $entityValidationResults = array(
            'usuario_id' => $this->isInvalidEntityId($this->usuarioModel, $usuario_id),
            'capacitacion_id' => $this->isInvalidEntityId($this->capacitacionModel, $capacitacion_id)
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

        if (!is_null($this->findUsuarioCapacitacion($usuario_id, $capacitacion_id))) {
            $response = [
                'message' => 'Este usuario ya esta asociado con esta capacitacion',
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $this->model->save($input);

        $usuarioCapacitacion = $this->findUsuarioCapacitacion($usuario_id, $capacitacion_id);

        $response = [
            'data' => $usuarioCapacitacion,
            'statusCode' => 201
        ];

        return $this->respond($response, 201);
    }

    public function delete(int $usuario_id, int $capacitacion_id)
    {
        $entityValidationResults = array(
            'usuario_id' => $this->isInvalidEntityId($this->usuarioModel, $usuario_id),
            'capacitacion_id' => $this->isInvalidEntityId($this->capacitacionModel, $capacitacion_id)
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

        $usuario_capacitacion = $this->findUsuarioCapacitacion($usuario_id, $capacitacion_id);

        if (is_null($usuario_capacitacion)) {
            $response = [
                'message' => 'Esta capacitacion no esta asociada a este usuario',
                'statusCode' => 400
            ];

            return $this->respond($response, 400);
        }

        $response = $this
            ->model
            ->where('usuario_capacitacion.capacitacion_id', $capacitacion_id)
            ->where('usuario_capacitacion.usuario_id', $usuario_id)
            ->delete();

        return $this->respond($response, 200);
    }

    private function findUsuarioCapacitacion(int $usuario_id, int $capacitacion_id)
    {
        $usuarioCapacitacion = $this
            ->model
            ->where('usuario_capacitacion.usuario_id', $usuario_id)
            ->where('usuario_capacitacion.capacitacion_id', $capacitacion_id)
            ->first();

        return $usuarioCapacitacion;
    }

    private function isInvalidEntityId(UsuarioModel|CapacitacionModel $model, int $id, string $message = 'Id invalido')
    {
        $entity = $model->find($id);

        return is_null($entity) ? $message : null;
    }
}
