<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsTagsModel extends Model
{
    protected $table            = 'news_tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['news_id', 'tag_id'];

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

    function getNewsTagsByUser($userId, $categoryId = null)
    {
        $query = $this->db->table('news_tags nt')
            ->select('t.id AS tag_id, t.name AS name_tag')
            ->join('tags t', 'nt.tag_id = t.id')
            ->join('news n', 'nt.news_id = n.id')
            ->where('n.user_id', $userId);
        if ($categoryId !== null) {
            $query->where('n.category_id', $categoryId);
        }

        $query->groupBy('t.id');
        return $query->get()->getResultArray();
    }
}
