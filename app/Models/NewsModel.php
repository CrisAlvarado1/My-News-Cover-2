<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table            = 'news';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'short_description', 'permanlink', 'date', 'url_image', 'news_source_id', 'user_id', 'category_id'];

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

    public function getNews($userId, $categoryId = null)
    {
        $query = $this->db->table('news n')
            ->select('n.*, c.name AS category_name, ns.name AS name_source')
            ->join('categories c', 'n.category_id = c.id')
            ->join('news_sources ns', 'n.news_source_id = ns.id')
            ->where('n.user_id', $userId);

        if ($categoryId !== null) {
            $query->where('n.category_id', $categoryId);
        }

        $query->orderBy('n.date', 'DESC');
        return $query->get()->getResultArray();
    }

    public function getNewsByTags($tagsSelected, $userId, $categoryId = null)
    {
        $query = $this->db->table('news n')
            ->select('n.*, c.name AS category_name, ns.name AS name_source')
            ->join('categories c', 'n.category_id = c.id')
            ->join('news_sources ns', 'n.news_source_id = ns.id')
            ->join('news_tags nt', 'n.id = nt.news_id')
            ->join('tags t', 'nt.tag_id = t.id')
            ->whereIn('t.id', $tagsSelected)
            ->where('n.user_id', $userId);

        if ($categoryId !== null) {
            $query->where('n.category_id', $categoryId);
        }

        $query->groupBy('n.id');
        return $query->get()->getResultArray();
    }
}
