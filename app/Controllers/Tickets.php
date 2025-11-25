<?php

namespace App\Controllers;

use App\Models\TicketsModel;
use CodeIgniter\RESTful\ResourceController;

class Tickets extends ResourceController
{
    protected $format = 'json';

    // =========================================
    // LISTAR TODOS LOS USUARIOS (CON JOINS)
    // =========================================
    public function index()
    {
        try {
            $model = new TicketsModel();
            $data = $model->getTicketsConJoin();

            return $this->respond([
                'status' => true,
                'dataset' => $data
            ]);

        } catch (\Throwable $th) {
            return $this->respond([
                'status' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    // =========================================
    // LEER UN USUARIO POR ID
    // =========================================
    public function readOne()
    {
        try {
            $id = $this->request->getPost('id_ticket');

            if (!$id) {
                return $this->respond(['status' => false, 'message' => 'Falta el ID'], 400);
            }

            $model = new TicketsModel();
            $data = $model->find($id);

            if (!$data) {
                return $this->respond(['status' => false, 'message' => 'Usuario no encontrado'], 404);
            }

            return $this->respond(['status' => true, 'dataset' => $data]);

        } catch (\Throwable $th) {
            return $this->respond(['status' => false, 'exception' => $th->getMessage()], 500);
        }
    }

    // =========================================
    // CREAR USUARIO
    // =========================================
      // =========================================
    // CREAR USUARIO
    // =========================================
    public function create()
{
    try {
        $validation = \Config\Services::validation();

        $rules = [
            'title'            => 'required|min_length[5]|max_length[250]',
            'desc'             => 'required|min_length[3]|max_length[250]',
            'id_servicio'      => 'required|integer'
        ];

        if (!$validation->setRules($rules)->withRequest($this->request)->run()) {
            return $this->respond([
                'status' => false, 
                'errors' => $validation->getErrors()
            ]);
        }
        $assignedTo = $this->request->getPost('id_asignado');

        if (!$assignedTo || $assignedTo === "" || $assignedTo === "null") {
            $assignedTo = null; // no asignado
        }

        $model = new TicketsModel();

        $idUsuario = session()->get('id_usuario');

        // Primer INSERT (sin ticket_number)
        $data = [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('desc'),
            'ticket_type'  => $this->request->getPost('id_tipo_ticket'),
            'priority'     => $this->request->getPost('prioridad'),
            'service_id'   => $this->request->getPost('id_servicio'),
            'created_by'   => $idUsuario,
            'assigned_to'  => $assignedTo
        ];

        if (!$model->insert($data)) {
            return $this->respond([
                'status' => false,
                'errors' => $model->errors()
            ]);
        }

        // Obtener el ID generado
        $ticketId = $model->getInsertID();

        // Generar número: TCK-000001
        $ticketNumber = sprintf("TCK-%06d", $ticketId);

        // Actualizar el ticket con el número generado
        $model->update($ticketId, ['ticket_number' => $ticketNumber]);

        return $this->respond([
            'status'  => true,
            'message' => 'Ticket generado correctamente',
            'ticket_number' => $ticketNumber
        ]);

    } catch (\Throwable $th) {
        return $this->respond([
            'status' => false,
            'exception' => $th->getMessage()
        ], 500);
    }
}


    // =========================================
    // ACTUALIZAR USUARIO
    // =========================================
    public function update($id = null)
{
    try {
        $id = $this->request->getPost('id_ticket');

        if (!$id) {
            return $this->respond(['status' => false, 'message' => 'Falta el ID'], 400);
        }

        $validation = \Config\Services::validation();

        $rules = [
            'title'        => 'required|min_length[5]|max_length[200]',
            'desc'         => 'required|min_length[3]|max_length[250]',
            'id_servicio'  => 'required|integer'
        ];

        if (!$validation->setRules($rules)->withRequest($this->request)->run()) {
            return $this->respond([
                'status' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $assignedTo = $this->request->getPost('id_asignado');

        if (!$assignedTo || $assignedTo === "" || $assignedTo === "null") {
            $assignedTo = null; // no asignado
        }

        $model = new TicketsModel();

        $data = [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('desc'),
            'ticket_type'  => $this->request->getPost('id_tipo_ticket'),
            'priority'     => $this->request->getPost('prioridad'),
            'service_id'   => $this->request->getPost('id_servicio'),
            'status'   => $this->request->getPost('estado'),
            'assigned_to'  => $assignedTo  // aquí ya va NULL o un ID válido
        ];

        $model->update($id, $data);

        return $this->respond([
            'status' => true,
            'message' => 'Ticket actualizado correctamente'
        ]);

    } catch (\Throwable $th) {
        return $this->respond(['status' => false, 'exception' => $th->getMessage()], 500);
    }
}

       public function deletelogic($id = null)
    {
        try {
            $idUsuario = session()->get('id_usuario');
            $id = $this->request->getPost('id_ticket');

            if (!$id) {
                return $this->respond(['status' => false, 'message' => 'Falta el ID'], 400);
            }

            $model = new TicketsModel();

            $data = [
                'status'           => 'closed',
                'closed_by'           => $idUsuario

            ];

            $model->update($id, $data);

            return $this->respond([
                'status' => true,
                'message' => 'El ticket ha sido cerrado.'
            ]);

        } catch (\Throwable $th) {
            return $this->respond(['status' => false, 'exception' => $th->getMessage()], 500);
        }
    }

    // =========================================
    // ELIMINAR USUARIO
    // =========================================
    public function delete($id = null)
    {
        try {
            $id = $this->request->getPost('id_usuario');

            if (!$id) {
                return $this->respond(['status' => false, 'message' => 'Falta el ID'], 400);
            }

            $model = new UsuarioModel();
            $model->delete($id);

            return $this->respond([
                'status' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);

        } catch (\Throwable $th) {
            return $this->respond([
                'status' => false,
                'exception' => $th->getMessage()
            ], 500);
        }
    }

    // =========================================
    // BÚSQUEDA DE USUARIOS
    // =========================================
    public function search() 
{
    try {
        //$search = $this->request->getPost('search') ?? $this->request->getGet('search');

        $search = $this->request->getPost('search');

        if (!$search) {
            return $this->respond([
                'status' => false,
                'message' => 'Debe ingresar un término de búsqueda'
            ]);
        }

        $model = new UsuarioModel();

        // Hacemos JOIN con tipos y estados para obtener los nombres
        $data = $model
            ->select('users.*, roles.name as tipo')
            ->join('roles', 'roles.id = users.role_id')
            ->groupStart()
                ->like('users.name', $search)
                ->orLike('users.last_name', $search)
                ->orLike('users.username', $search)
            ->groupEnd()
            ->findAll();

        return $this->respond([
            'status' => true,
            'dataset' => $data
        ]);

    } catch (\Throwable $th) {
        return $this->respond(['status' => false, 'exception' => $th->getMessage()], 500);
    }
}


    public function getServices()
{
    try {
        $db = \Config\Database::connect();
        $query = $db->table('services')->select('id AS id, description AS desc')->get()->getResult();

        return $this->respond([
            'status' => true,
            'dataset' => $query
        ]);

    } catch (\Throwable $th) {
        return $this->respond([
            'status' => false,
            'exception' => $th->getMessage()
        ]);
    }
}

public function getUsuarios()
{
    try {
        $db = \Config\Database::connect();
        $query = $db->table("users")->select("id AS id, users.name ||' '|| users.last_name AS nombre")->get()->getResult();

        return $this->respond([
            'status' => true,
            'dataset' => $query
        ]);

    } catch (\Throwable $th) {
        return $this->respond([
            'status' => false,
            'exception' => $th->getMessage()
        ]);
    }
}


}


