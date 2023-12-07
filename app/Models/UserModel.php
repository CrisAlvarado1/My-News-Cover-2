<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'first_name', 'last_name', 'role_id', 'password', 'phone_number', 'is_public'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Check if an email already exists in the user database.
     *
     * @param string $email The email to check for existence.
     *
     * @return bool True if the email exists, false otherwise.
     */
    public function isEmailExists($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Get a user by their email address.
     *
     * @param string $email The email of the user to retrieve.
     *
     * @return array|null An array representing the user if found, or null if not found.
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Check if a user has an administrator role.
     *
     * @param int $userId The ID of the user to check.
     *
     * @return bool True if the user has an administrator role, false otherwise.
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role_id'] == 1;
    }
}
