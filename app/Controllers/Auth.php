<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    // Vista del login
    public function loginView()
    {
        return view('dashboard/index');
    }
    
      public function index()
{
    try {
        $data = $this->model->findAll();

        return $this->respond([
            'status'  => true,
            'dataset' => $data
        ]);

    } catch (\Throwable $th) {
        // Captura cualquier error del modelo o la BD
        return $this->respond([
            'status'    => false,
            'exception' => $th->getMessage(),   // Mensaje del error
            'line'      => $th->getLine(),      // Línea donde ocurrió
            'file'      => $th->getFile()       // Archivo donde ocurrió
        ], 500);
    }
}

    // Verificar si existen usuarios
    public function exists()
    {
        try {
            $model = new UsuarioModel();
            $count = $model->countAllResults();

            return $this->respond([
                'status' => $count > 0,
                'message' => $count > 0 ? 'Usuarios existen' : null,
                'exception' => $count > 0 ? null : 'No hay usuarios registrados'
            ]);
        } catch (\Throwable $th) {
            return $this->respond([
                'status' => false,
                'exception' => $th->getMessage()
            ], 500);
        }
    }

    public function login()
    {
        try {
            $alias = $this->request->getPost('alias_usuario');
            $clave = $this->request->getPost('clave_usuario');

            if (!$alias || !$clave) {
                return $this->respond([
                    'status' => false,
                    'exception' => 'Faltan datos'
                ], 400);
            }

            $model = new UsuarioModel();

            // Buscar usuario por alias
            $user = $model->where('username', $alias)->first();

            // Usuario no existe
            if (!$user) {
                return $this->respond([
                    'status' => false,
                    'exception' => 'El usuario no existe o existe una clave incorrecta.'
                ], 404);
            }

            // Verificar contraseña
            if (!password_verify($clave, $user['password_hash'])) {
                return $this->respond([
                    'status' => false,
                    'exception' => 'Contraseña incorrecta'
                ], 401);
            }

            // Iniciar sesión
            session()->set([
                'id_usuario'    => $user['id'],
                'alias_usuario' => $user['username'],
                'login'         => true
            ]);

            return $this->respond([
                'status' => true,
                'message' => 'Autenticación exitosa'
            ]);

        } catch (\Throwable $th) {
            return $this->respond([
                'status' => false,
                'exception' => $th->getMessage()
            ], 500);
        }
    }

    public function logOut()
{
    // Destruye toda la sesión
    session()->destroy();

    return $this->respond([
        'status' => true,
        'message' => 'Sesión cerrada correctamente'
    ]);
}
 
}
