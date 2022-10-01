<?php

namespace App\Controllers;

use App\Entities\MisionEntity;
use App\Models\InstitucionModel;
use App\Models\MisionFechasModel;
use App\Models\MisionFotoModel;
use App\Models\MisionModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;

class Mision extends BaseController
{
    use ResponseTrait;

    protected MisionModel $model;
    protected InstitucionModel $institucionModel;
    protected MisionFechasModel $misionFechasModel;
    protected MisionFotoModel $misionFotoModel;
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->model = new MisionModel();
        $this->institucionModel = new InstitucionModel();
        $this->misionFechasModel = new MisionFechasModel();
        $this->misionFotoModel = new MisionFotoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index($id = null)
    {
        $data = null;
        $query = $this
            ->model
            ->join('institucion', 'institucion.institucion_id = mision.institucion_id');

        if (!is_null($id)) {
            $data = $query->find($id);

            if (is_null($data)) {
                $response = [
                    'statusCode' => 400,
                    'message' => 'Invalid id'
                ];

                return $this->respond($response, 400);
            }

            $data->fechas = $this->misionFechasModel->where('mision_fechas.mision_id', $id)->findAll();
            $data->fotos = $this->misionFotoModel->where('mision_foto.mision_id', $id)->findAll();
            $data->usuarios = $this
                ->usuarioModel
                ->select('usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido, rol.rol_nombre, departamento.departamento_nombre')
                ->join('usuario_mision', 'usuario_mision.usuario_id = usuario.usuario_id', 'inner')
                ->join('rol', 'rol.rol_id = usuario.rol_id', 'left')
                ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                ->where('usuario_mision.mision_id', $id)
                ->findAll();
        } else {
            $data = $query->findAll();

            $data = array_map(function ($mision) {
                $mision->fechas = $this->misionFechasModel->where('mision_fechas.mision_id', $mision->mision_id)->findAll();
                $mision->fotos = $this->misionFotoModel->where('mision_foto.mision_id', $mision->mision_id)->findAll();
                $mision->usuarios = $this
                    ->usuarioModel
                    ->select('usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido, rol.rol_nombre, departamento.departamento_nombre')
                    ->join('usuario_mision', 'usuario_mision.usuario_id = usuario.usuario_id', 'inner')
                    ->join('rol', 'rol.rol_id = usuario.rol_id', 'left')
                    ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                    ->where('usuario_mision.mision_id', $mision->mision_id)
                    ->findAll();

                return $mision;
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
        $mision = $this
            ->model
            ->join('institucion', 'institucion.institucion_id = mision.institucion_id')
            ->find($id);

        if (is_null($mision)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo mision_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->save($mision);

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

        $mision = $entity;
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
        $id = $entity->mision_id ?? $this->model->getInsertID();

        // find inserted mision
        $mision = $mision && !$hasChanged
            ? $mision
            : $this
            ->model
            ->join('institucion', 'institucion.institucion_id = mision.institucion_id')
            ->find($id);

        $response = [
            'statusCode' => 201,
            'data' => $mision
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
