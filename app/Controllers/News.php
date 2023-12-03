<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;
use App\Models\NewsTagsModel;

class News extends BaseController
{
    public function index()
    {
        $data    = $this->loadCommonData();
        $this->renderNews($data);
    }

    public function filterNews($categoryId)
    {
        $data    = $this->loadCommonData($categoryId);
        $this->renderNews($data);
    }

    private function loadCommonData($categoryId = null)
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $newsModel        = Model(NewsModel::class);
        $newsTagsModel    = Model(NewsTagsModel::class);
        $userId           = session()->get('user_id');
        $data['title']    = 'My Cover';
        $data['filters']  = $newsSourcesModel->getDistinctCategoriesByUserId($userId);

        if ($categoryId !== null) {
            $data['allNews']    = $newsModel->getNews($userId, $categoryId);
            $data['categoryId'] = $categoryId;
            $data['tags']       = $newsTagsModel->getNewsTagsByUser($userId);
        } else {
            $data['allNews'] = $newsModel->getNews($userId);
            $data['tags']    = $newsTagsModel->getNewsTagsByUser($userId, $categoryId);
        }

        return $data;
    }

    public function searchInAllNews()
    {
        $keywords = $this->request->getVar('keywords');
        $data     = $this->loadCommonData();

        if (!empty($keywords)) {
            $data['allNews'] = $this->searchNewsWithKeywords($data, $keywords);
            $data['dataKeywords'] = $keywords;
        }
        $this->renderNews($data);
    }

    public function searchInCategoryNews($categoryId)
    {
        $keywords = $this->request->getVar('keywords');
        $data     = $this->loadCommonData($categoryId);

        if (!empty($keywords)) {
            $data['allNews'] = $this->searchNewsWithKeywords($data, $keywords);
            $data['dataKeywords'] = $keywords;
        }
        $this->renderNews($data);
    }

    private function searchNewsWithKeywords($data, $keywords)
    {
        $filteredNews = array_filter($data['allNews'], function ($news) use ($keywords) {
            // Convert to lowercase for a case-insensitive comparison
            $lowerKeywords = strtolower($keywords);

            // Check if the title or content contains all the keywords
            $containsKeywords =
                stripos($news['title'], $lowerKeywords) !== false ||
                stripos($news['short_description'], $lowerKeywords) !== false;

            return $containsKeywords;
        });

        return $filteredNews;
    }

    private function renderNews($data)
    {
        $content = view('users/news/index', $data);
        return parent::renderTemplate($content, $data);
    }
}
