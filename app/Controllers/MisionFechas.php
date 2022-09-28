<?php

namespace App\Controllers;

use App\Models\MisionFechasModel;
use App\Models\MisionModel;
use CodeIgniter\API\ResponseTrait;

class MisionFechas extends BaseController
{
    use ResponseTrait;

    protected MisionFechasModel $model;
    protected MisionModel $misionModel;

    public function __construct()
    {
        $this->model = new MisionFechasModel();
        $this->misionModel = new MisionModel();
    }

    public function index()
    {
        $query = $this->model->join('mision', 'mision.mision_id = mision_fechas.mision_id', 'left');

        $params = $this->request->getVar();
        $rules = ['mision_id' => $this->model->rules['mision_id']];


        if (!is_null($params)) {
            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                $response = [
                    'statusCode' => 400,
                    'errors' => $errors
                ];
                return $this->respond($response, 400);
            }

            $query = $query->where('mision_fechas.mision_id', $params['mision_id']);
        }

        $fechas = $query->findAll();

        $response = [
            'data' => $fechas,
            'statusCode' => 200,
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
        $misionFecha = $this->model->find($id);

        if (is_null($misionFecha)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo mission_fechas_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->save();

        return $this->respond($result['response'], $result['statusCode']);
    }

    public function delete(int $id)
    {
        $misionFecha = $this->model->find($id);

        if (is_null($misionFecha)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo mission_fechas_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->model->delete($id);

        return $this->respond($result, 200);
    }

    private function save()
    {
        $input = $this->request->getPost();

        if (!count($input)) {

            $response = [
                'statusCode' => 400,
                'message' => 'Ningún parámetro fue enviado',
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        if (!$this->validate($this->model->rules)) {
            $errors = $this->validator->getErrors();

            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        $mision = $this->misionModel->find($input['mision_id']);

        if (is_null($mision)) {
            $errors = (object) ['mision_id' => 'Id invalido'];

            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        if (!($this->model->save($input))) {
            $errors = $this->model->errors();

            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];

            return ['response' => $response, 'statusCode' => 400];
        }

        $id = $this->model->getInsertID();

        $misionFecha = $this
            ->model
            ->join('mision', 'mision.mision_id = mision_fechas.mision_id')
            ->find($id);

        $response = [
            'statusCode' => 201,
            'data' => $misionFecha,
        ];

        return ['response' => $response, 'statusCode' => 201];
    }
}
