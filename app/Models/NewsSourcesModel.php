<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsSourcesModel extends Model
{
    protected $table            = 'news_sources';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['url', 'name', 'category_id', 'user_id'];

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
     * Get news sources based on user ID.
     *
     * @param int $userId The user ID.
     *
     * @return array An array of news sources data with associated category names.
     */
    public function getNewsSourcesByUserId($userId)
    {
        $query = $this->select('news_sources.*, categories.name AS category_name')
            ->join('categories', 'news_sources.category_id = categories.id')
            ->where('news_sources.user_id', $userId)
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get distinct categories associated with news sources for a user.
     *
     * @param int $userId The user ID.
     *
     * @return array An array of distinct category data.
     */
    public function getDistinctCategoriesByUserId($userId)
    {
        $query = $this->distinct()
            ->select('c.id AS category_id, c.name AS category_name')
            ->from('news_sources ns')
            ->join('categories c', 'ns.category_id = c.id')
            ->where('ns.user_id', $userId)
            ->get();

        return $query->getResultArray();
    }
}
