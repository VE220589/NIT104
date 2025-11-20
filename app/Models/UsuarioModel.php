<?php 

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true; // IMPORTANTE PARA UUID
    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'email',
        'password_hash',
        'name',
        'last_name',
        'role_id',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    public function getUsuariosConJoin()
    {
        return $this->select('users.*, roles.name as tipo')
                    ->join('roles', 'roles.id = users.role_id')
                    ->findAll();
    }
}

