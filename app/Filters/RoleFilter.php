<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use function PHPUnit\Framework\isNull;

class RoleFilter implements FilterInterface
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
        $roles = $arguments;
        $model = new UsuarioModel();
        $user = $model
            ->where('usuario.usuario_username', $request->user->usuario_username)
            ->join('rol', 'rol.rol_id = usuario.rol_id')
            ->first();

        $request->user = $user;

        if (
            !is_null($roles)
            && count($roles) > 0
            && $user->rol_nombre !== 'Admin'
            && !in_array($user->rol_nombre, $roles)
        ) {
            $response = service('response');
            $response->setBody('Unauthorized');
            $response->setBody(json_encode(['statusCode' => 403, 'message' => 'Forbidden resource']));
            return $response;
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
        //
    }
}
