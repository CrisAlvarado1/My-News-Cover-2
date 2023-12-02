<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;

class News extends BaseController
{
    public function index()
    {
        $data    = $this->loadCommonData();
        $content = view('users/news/index', $data);

        return parent::renderTemplate($content, $data);
    }

    public function filterNews($categoryId)
    {
        $data    = $this->loadCommonData($categoryId);
        $content = view('users/news/index', $data);

        return parent::renderTemplate($content, $data);
    }

    private function loadCommonData($categoryId = null)
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $newsModel        = Model(NewsModel::class);
        $userId           = session()->get('user_id');
        $data['title']    = 'My Cover';
        $data['filters']  = $newsSourcesModel->getDistinctCategoriesByUserId($userId);

        if ($categoryId !== null) {
            $data['allNews']    = $newsModel->getNews($userId, $categoryId);
            $data['categoryId'] = $categoryId;
        } else {
            $data['allNews'] = $newsModel->getNews($userId);
        }

        return $data;
    }
}
