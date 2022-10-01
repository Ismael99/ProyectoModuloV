<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class QueryFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $models = array('capacitacion', 'mision');
        $currentModel = $arguments[0];

        if (!in_array($currentModel, $models)) {
            $response = service('response');
            $response->setHeader('Content-Type', 'application/json');
            $response->setBody(json_encode(['statusCode' => 422, 'message' => 'Unprocessable Entity']));
            $response->setStatusCode(422);

            return $response;
        }

        $model = new UsuarioModel();
        $user = $model
            ->where('usuario.usuario_username', $request->user->usuario_username)
            ->join('rol', 'rol.rol_id = usuario.rol_id')
            ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
            ->first();

        $request->user = $user;

        if ($user->rol_nombre !== 'Admin') {
            if ($user->rol_nombre === 'Gerente') {
                $request->where = 'departamento.departamento_id = ' . $user->departamento_id;
            } else {
                $request->where = 'usuario_' . $currentModel . '.' . 'usuario_id = ' . $user->usuario_id;
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
