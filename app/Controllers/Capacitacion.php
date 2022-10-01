<?php

namespace App\Controllers;

use App\Models\InstitucionModel;
use App\Models\CapacitacionFechasModel;
use App\Models\CapacitacionFotoModel;
use App\Models\CapacitacionModel;
use App\Models\ModalidadModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class Capacitacion extends BaseController
{
    use ResponseTrait;

    protected CapacitacionModel $model;
    protected InstitucionModel $institucionModel;
    protected CapacitacionFechasModel $capacitacionFechasModel;
    protected CapacitacionFotoModel $capacitacionFotoModel;
    protected UsuarioModel $usuarioModel;
    protected ModalidadModel $modalidadModel;

    public function __construct()
    {
        $this->model = new CapacitacionModel();
        $this->institucionModel = new InstitucionModel();
        $this->capacitacionFechasModel = new CapacitacionFechasModel();
        $this->capacitacionFotoModel = new CapacitacionFotoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->modalidadModel = new ModalidadModel();
    }

    public function index($id = null)
    {
        $data = null;
        $query = $this
            ->model
            ->select('capacitacion.*, institucion.*, modalidad.*')
            ->join('institucion', 'institucion.institucion_id = capacitacion.institucion_id', 'left')
            ->join('modalidad', 'modalidad.modalidad_id = capacitacion.modalidad_id', 'left');

        if (!is_null($this->request->where)) {
            $query = $query
                ->join('usuario_capacitacion', 'usuario_capacitacion.capacitacion_id = capacitacion.capacitacion_id', 'left')
                ->join('usuario', 'usuario.usuario_id = usuario_capacitacion.usuario_id', 'left')
                ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                ->where($this->request->where);
        }

        if (!is_null($id)) {
            $data = $query->find($id);

            if (is_null($data)) {
                $response = [
                    'statusCode' => 400,
                    'message' => 'Invalid id'
                ];

                return $this->respond($response, 400);
            }

            $data->fechas = $this->capacitacionFechasModel->where('capacitacion_fechas.capacitacion_id', $id)->findAll();
            $data->fotos = $this->capacitacionFotoModel->where('capacitacion_foto.capacitacion_id', $id)->findAll();
            $usuariosQuery = $this
                ->usuarioModel
                ->select('usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido, rol.rol_nombre, departamento.departamento_nombre')
                ->join('usuario_capacitacion', 'usuario_capacitacion.usuario_id = usuario.usuario_id', 'inner')
                ->join('rol', 'rol.rol_id = usuario.rol_id', 'left')
                ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                ->where('usuario_capacitacion.capacitacion_id', $data->capacitacion_id);

            if (!is_null($this->request->where)) {
                $usuariosQuery = $usuariosQuery
                    ->where($this->request->where);
            }

            $data->usuarios = $usuariosQuery->findAll();
        } else {
            $data = $query->findAll();

            $data = array_map(function ($capacitacion) {
                $capacitacion->fechas = $this->capacitacionFechasModel->where('capacitacion_fechas.capacitacion_id', $capacitacion->capacitacion_id)->findAll();
                $capacitacion->fotos = $this->capacitacionFotoModel->where('capacitacion_foto.capacitacion_id', $capacitacion->capacitacion_id)->findAll();
                $usuariosQuery = $this
                    ->usuarioModel
                    ->select('usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido, rol.rol_nombre, departamento.departamento_nombre')
                    ->join('usuario_capacitacion', 'usuario_capacitacion.usuario_id = usuario.usuario_id', 'inner')
                    ->join('rol', 'rol.rol_id = usuario.rol_id', 'left')
                    ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                    ->where('usuario_capacitacion.capacitacion_id', $capacitacion->capacitacion_id);

                if (!is_null($this->request->where)) {
                    $usuariosQuery = $usuariosQuery
                        ->where($this->request->where);
                }

                $capacitacion->usuarios = $usuariosQuery->findAll();

                return $capacitacion;
            }, $data);
        }

        $response = [
            'statusCode' => 200,
            'data' => $data
        ];

        return $this->respond($response, 200);
    }

    public function create()
    {
        $result = $this->save();

        return $this->respond($result['response'], $result['statusCode']);
    }

    public function update(int $id)
    {
        $capacitacion = $this
            ->model
            ->join('institucion', 'institucion.institucion_id = capacitacion.institucion_id', 'left')
            ->join('modalidad', 'modalidad.modalidad_id = capacitacion.modalidad_id', 'left')
            ->find($id);

        if (is_null($capacitacion)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo capacitacion_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->save($capacitacion);

        return $this->respond($result['response'], $result['statusCode']);
    }

    private function save($entity = null)
    {
        $input = $this->request->getPost();

        // validate input was sent, do not accept empty body
        if (!count($input)) {
            $response = [
                'statusCode' => 400,
                'message' => 'Ningún parámetro fue enviado',
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        // validate foreignKeys
        $entityValidationResults = array(
            'institucion_id' => $this->isInvalidEntityId($this->institucionModel, $input['institucion_id']),
            'modalidad_id' => $this->isInvalidEntityId($this->modalidadModel, $input['modalidad_id']),
        );

        // filter error messages
        $entityErrors = array_filter($entityValidationResults, function ($error) {
            return !is_null($error);
        });

        // if any errors are encountered, return bad request
        if (count($entityErrors) > 0) {
            $response = [
                'errors' => (object) $entityErrors,
                'statusCode' => 400
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        $capacitacion = $entity;
        /**
         * Since model->save throws an exception when nothing has changed,
         * we need to handle this flag by checking if the new input is different
         * from the old one, so if not we will avoid to save
         */
        $hasChanged = false;

        if (!is_null($entity)) {
            foreach ($input as $key => $value) {
                if (!$hasChanged) {
                    $hasChanged = $entity->{$key} !== $value;
                }

                $entity->{$key} = $value;
            }
        } else {
            $entity = $input;
        }

        // save model
        if ($hasChanged && !$this->model->save($entity)) {
            $errors = $this->model->errors();

            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        // get inserted PK 
        $id = $entity->capacitacion_id ?? $this->model->getInsertID();

        // find inserted capacitacion
        $capacitacion = $capacitacion && !$hasChanged
            ? $capacitacion
            : $this
            ->model
            ->join('institucion', 'institucion.institucion_id = capacitacion.institucion_id', 'left')
            ->join('modalidad', 'modalidad.modalidad_id = capacitacion.modalidad_id', 'left')
            ->find($id);

        $response = [
            'statusCode' => 201,
            'data' => $capacitacion
        ];

        return ['response' => $response, 'statusCode' => 201];
    }

    private function isInvalidEntityId($model, int $id, string $message = 'Id invalido')
    {
        $entity = $model->find($id);

        if (is_null($entity)) {
            return $message;
        }

        return null;
    }
}
