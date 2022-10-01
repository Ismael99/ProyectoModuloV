<?php

namespace App\Controllers;

use App\Entities\UsuarioEntity;
use App\Models\DepartamentoModel;
use App\Models\UsuarioModel;
use App\Models\RolModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Usuario extends BaseController
{

    use ResponseTrait;

    public function create()
    {
        $usuarioModel = new UsuarioModel();
        $rolModel = new RolModel();
        $departamentoModel = new DepartamentoModel();

        $usuario = $this->request->getPost();
        $usuario['usuario_username'] = strtolower(substr($usuario['usuario_nombre'], 0, 2) . substr($usuario['usuario_apellido'], 0, 2) . time());

        if ($rolModel->where("rol.rol_id", $usuario["rol_id"])->first() == null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'Rol no valido'
            ];
            return $this->respond($response);
        }

        if ($departamentoModel->where("departamento.departamento_id", $usuario['departamento_id'])->first() == null) {
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
            return $this->respond($response, 400);
        } else {
            
            unset($usuario->usuario_password);
            $usuarioModel->save($usuario);
            $usuario = $usuarioModel->where('usuario.usuario_username', $usuario['usuario_username'])->first();

            $response = [
                'statusCode' => 201,
                'data' => $usuario
            ];
            return $this->respond($response);
        }
    }

    public function get()
    {
        $usuarioModel = new UsuarioModel();
        $usuarioData = $usuarioModel->findAll();
        $response = [
            'statusCode' => 200,
            'data' => $usuarioData
        ];
        return $this->respond($response);
    }

     public function update($usuario_id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = new UsuarioEntity();
        $usuario = $this->request->getVar();

        $usuario_id_num = (int) $usuario_id;

        $usuarioToUpdate = $usuarioModel->where("usuario.usuario_id", $usuario_id_num)->first();

        if ($usuario_id_num <= 0 || $usuarioToUpdate==null) {
            $response = [
                'statusCode' => 400,
                'errors' => 'El usuario_id no es válido'
            ];
            return $this->respond($response, 400);
        }
 
        $dataPrev = [
            "usuario_username" => $usuarioToUpdate->usuario_username,
            "usuario_password" => $usuarioToUpdate->usuario_password,
            "usuario_nombre" => $usuarioToUpdate->usuario_nombre,
            "usuario_apellido" => $usuarioToUpdate->usuario_apellido,
            "usuario_nacimiento" => $usuarioToUpdate->usuario_nacimiento,
            "usuario_dui" => $usuarioToUpdate->usuario_dui,
            "usuario_telefono" => $usuarioToUpdate->usuario_telefono,
            "rol_id" => $usuarioToUpdate->rol_id,
            "departamento_id" => $usuarioToUpdate->departamento_id,
        ];

        $data = array_merge($dataPrev, $usuario);

        //Para llaves foraneas
        $rolModel = new RolModel();
        if($rolModel->where("rol.rol_id", (int) $data["rol_id"])->first() ==null ){
            $response = [
                'statusCode' => 400,
                'errors' => 'El rol_id no es válido'
            ];
            return $this->respond($response, 400);
        }
        $departamentoModel = new DepartamentoModel();
        if($departamentoModel->where("departamento.departamento_id", (int) $data["departamento_id"])->first() ==null ){
            $response = [
                'statusCode' => 400,
                'errors' => 'El departamento_id no es válido'
            ];
            return $this->respond($response, 400);
        }

        $array_keys_data = array_keys($data);
        foreach($array_keys_data as $key){
            if($data[$key] == $dataPrev[$key]){
                unset($data[$key]);
            }
        };

        $validation = \Config\Services::validation();
        $rules = $usuarioModel->rulesUpdate;
        if($data["departamento_id"] != null){
            $rules = array_merge($rules, [  
                'departamento_id' => [
                    'rules' => 'integer',
                    'errors' => [
                        'integer' => 'El campo departamento_id es un numero entero',
                    ]
                ],
            ] );
        }
        if($data["rol_id"] != null){
            $rules = array_merge($rules, [  
                'rol_id' => [
                    'rules' => 'integer',
                    'errors' => [
                        'integer' => 'El campo rol_id es un numero entero',
                    ]
                ],
            ] );
        }
        if($data["usuario_nacimiento"] != null){
            $rules = array_merge($rules, [  
                'usuario_nacimiento' => [
                    // TODO: add date format validation
                    'rules' => 'valid_date[Y-m-d]',
                    'errors' => [
                        'valid_date' => 'El campo usuario_nacimiento debe ser una fecha valida: Y-m-d',
                    ]
                ],
            ] );
        }
        
        $validation->setRules($rules);
        
        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            // echo $errors;
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response);
        } else {
            if(count($data) > 0){
                $usuarioModel->update($usuario_id_num, $data);
            }
            $usuarioUpdated = $usuarioModel->where("usuario.usuario_id", $usuario_id_num)->first();
            $response = [
                'statusCode' => 201,
                'data' => $usuarioUpdated
            ];
            return $this->respond($response);
        }
    }

    public function delete($usuario_id)
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
            return $this->respond($response, 400);
        } else {
            $usuarioModel->delete($usuario_id_num);
            $response = [
                'statusCode' => 200,
                'msg' => 'Usuario eliminado'
            ];
            return $this->respond($response);
        }
        return $this->respond($response);
    }

    public function login()
    {
        $rules = [
            'usuario_username' => 'required|min_length[6]|max_length[255]',
            'usuario_password' => 'required|min_length[6]|max_length[255]|validateUser[usuario_username,usuario_password]',
        ];

        $errors = [
            'usuario_password' => [
                'validateUser' => "Email or Password didn't match",
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            $errors = $this->validator->getErrors();
            $response = [
                'statusCode' => 400,
                'errors' => $errors
            ];
            return $this->respond($response, 400);
        } else {
            $user = new UsuarioEntity();
            $userModel = new UsuarioModel();

            $user = $userModel
                //->select('usuario.*, rol.nombre as rol_nombre, departamento.nombre as departamento_nombre')
                ->where('usuario_username', $this->request->getPost()['usuario_username'])
                ->join('rol', 'rol.rol_id = usuario.rol_id', 'left')
                ->join('departamento', 'departamento.departamento_id = usuario.departamento_id', 'left')
                ->first();


            unset($user->usuario_password);
            $token = $this->buildToken($user);
            unset($user->usuario_username);

            $response = [
                'statusCode' => 200,
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ];

            return $this->respond($response, 200);
        }
    }

    private function buildToken(UsuarioEntity $user)
    {
        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = [
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            'usuario_username' => $user->usuario_username,
            'sub' => $user->usuario_id,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $token;
    }
}
