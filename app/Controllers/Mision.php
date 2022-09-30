<?php

namespace App\Controllers;

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
}
