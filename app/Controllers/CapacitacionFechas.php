<?php

namespace App\Controllers;

use App\Models\CapacitacionFechasModel;
use App\Models\CapacitacionModel;
use CodeIgniter\API\ResponseTrait;

class CapacitacionFechas extends BaseController
{
    use ResponseTrait;

    protected CapacitacionFechasModel $model;
    protected CapacitacionModel $capacitacionModel;

    public function __construct()
    {
        $this->model = new CapacitacionFechasModel();
        $this->capacitacionModel = new CapacitacionModel();
    }

    public function index()
    {
        $query = $this->model->join('capacitacion', 'capacitacion.capacitacion_id = capacitacion_fechas.capacitacion_id', 'left');

        $params = $this->request->getVar();
        $rules = ['capacitacion_id' => $this->model->rules['capacitacion_id']];


        if (count($params) > 0) {
            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                $response = [
                    'statusCode' => 400,
                    'errors' => $errors
                ];
                return $this->respond($response, 400);
            }

            $query = $query->where('capacitacion_fechas.capacitacion_id', $params['capacitacion_id']);
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
        $capacitacionFecha = $this->model->find($id);

        if (is_null($capacitacionFecha)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo capacitacion_fechas_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->save($id);

        return $this->respond($result['response'], $result['statusCode']);
    }

    public function delete(int $id)
    {
        $capacitacionFecha = $this->model->find($id);

        if (is_null($capacitacionFecha)) {
            $response = [
                'statusCode' => 400,
                'message' => 'El campo capacitacion_fechas_id es invalido',
            ];

            return $this->respond($response, 400);
        }

        $result = $this->model->delete($id);

        return $this->respond($result, 200);
    }

    private function save($id = null)
    {
        $input = $this->request->getPost();
        $input['capacitacion_fechas_id'] = $id;

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

        $capacitacion = $this->capacitacionModel->find($input['capacitacion_id']);

        if (is_null($capacitacion)) {
            $errors = (object) ['capacitacion_id' => 'Id invalido'];

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

        $id = $id ?? $this->model->getInsertID();

        $capacitacionFecha = $this
            ->model
            ->join('capacitacion', 'capacitacion.capacitacion_id = capacitacion_fechas.capacitacion_id')
            ->find($id);

        $response = [
            'statusCode' => 201,
            'data' => $capacitacionFecha,
        ];

        return ['response' => $response, 'statusCode' => 201];
    }
}
