<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;
use App\Models\NewsTagsModel;
use App\Models\UserModel;

class News extends BaseController
{
    protected $newsSourcesModel;
    protected $newsModel;
    protected $newsTagsModel;
    protected $userId;

    public function __construct()
    {
        $this->newsSourcesModel = Model(NewsSourcesModel::class);
        $this->newsModel        = Model(NewsModel::class);
        $this->newsTagsModel    = Model(NewsTagsModel::class);
        $this->userId           = session()->get('user_id');
    }

    public function index()
    {
        $data            = $this->loadCommonData();
        $data['allNews'] = $this->newsModel->getNews($this->userId);
        $this->renderNews($data);
    }

    public function filterNewsByCategory($categoryId)
    {
        $data            = $this->loadCommonData($categoryId);
        $data['allNews'] = $this->newsModel->getNews($this->userId, $categoryId);
        $this->renderNews($data);
    }

    private function loadCommonData($categoryId = null)
    {
        $data['title']    = 'My Cover';
        $data['filters']  = $this->newsSourcesModel->getDistinctCategoriesByUserId($this->userId);

        if ($categoryId === null) {
            $data['tags'] = $this->newsTagsModel->getNewsTagsByUser($this->userId);
        } else {
            $data['tags']       = $this->newsTagsModel->getNewsTagsByUser($this->userId, $categoryId);
            $data['categoryId'] = $categoryId;
        }

        return $data;
    }

    public function searchInAllNews()
    {
        $keywords        = $this->request->getVar('keywords');
        $data            = $this->loadCommonData();
        $data['allNews'] = $this->newsModel->getNews($this->userId);

        if (!empty($keywords)) {
            $data['allNews']      = $this->searchNewsWithKeywords($data, $keywords);
            $data['dataKeywords'] = $keywords;
        }
        $this->renderNews($data);
    }

    public function searchInCategoryNews($categoryId)
    {
        $keywords        = $this->request->getVar('keywords');
        $data            = $this->loadCommonData($categoryId);
        $data['allNews'] = $this->newsModel->getNews($this->userId, $categoryId);

        if (!empty($keywords)) {
            $data['allNews']      = $this->searchNewsWithKeywords($data, $keywords);
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

    public function filterNewsByTagsInAllNews()
    {
        $tagsSelected = $this->request->getGet('tagsNews');

        if (!empty($tagsSelected)) {
            $data                 = $this->loadCommonData();
            $data['tagsSelected'] = $tagsSelected;
            $data['allNews']      = $this->newsModel->getNewsByTags($tagsSelected, $this->userId);

            $this->renderNews($data);
        } else {
            return redirect()->to('users/news/index');
        }
    }

    public function filterNewsByTagsInCategoryNews($categoryId)
    {
        $tagsSelected = $this->request->getGet('tagsNews');

        if (!empty($tagsSelected)) {
            $data                 = $this->loadCommonData($categoryId);
            $data['tagsSelected'] = $tagsSelected;
            $data['allNews']      = $this->newsModel->getNewsByTags($tagsSelected, $this->userId, $categoryId);

            $this->renderNews($data);
        } else {
            $this->filterNewsByCategory($categoryId);
        }
    }

    public function publicCover()
    {
        $userModel        = Model(UserModel::class);
        $data['title']    = "Public Cover";
        $data['isPublic'] = $userModel->select('is_public')->where('id', $this->userId)->first()['is_public'] ?? false;
        $content          = view('users/news/public', $data);
        return parent::renderTemplate($content, $data);
    }

    public function makePublicCover()
    {
        $userModel        = Model(UserModel::class);
        $data['title']    = "Public Cover";
        $confirm          = $this->request->getPost('confirm');
        $disconfirm       = $this->request->getPost('disconfirm');

        if (!empty($confirm) && empty($disconfirm)) {
            $userModel->where('id', $this->userId)->set(['is_public' => true])->update();
            $nameUser           = session()->get('name');
            $lastNameUser       = session()->get('lastName');
            $data['accessLink'] = 'http://mynewscover2.com/index.php/users/' . $nameUser . '/' . $lastNameUser . '';
        } else {
            $userModel->where('id', $this->userId)->set(['is_public' => false])->update();
        }

        $data['isPublic'] = $userModel->select('is_public')->where('id', $this->userId)->first()['is_public'] ?? false;
        $content          = view('users/news/public', $data);
        return parent::renderTemplate($content, $data);
    }
}
