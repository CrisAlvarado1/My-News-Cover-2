<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;
use App\Models\UserModel;

class PublicCover extends BaseController
{
    protected $newsSourcesModel;
    protected $newsModel;
    protected $newsTagsModel;
    protected $userModel;

    public function __construct()
    {
        $this->newsSourcesModel = model(NewsSourcesModel::class);
        $this->newsModel        = model(NewsModel::class);
        $this->newsTagsModel    = model(NewsTagsModel::class);
        $this->userModel        = model(UserModel::class);
    }

    public function index($name, $lastName, $userId)
    {
        $data            = $this->loadCommonData($name, $lastName, $userId);
        $data['allNews'] = $this->newsModel->getNews($userId);

        if ($this->validateIsPublic($userId)) {
            $content = view('users/news/index', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function newsByCategory($name, $lastName, $userId, $categoryId)
    {
        $data               = $this->loadCommonData($name, $lastName, $userId);
        $data['allNews']    = $this->newsModel->getNews($userId, $categoryId);
        $data['categoryId'] = $categoryId;

        if ($this->validateIsPublic($userId)) {
            $content = view('users/news/index', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('/');
        }
    }

    private function validateIsPublic($userId)
    {
        $isPublic           = $this->userModel->select('is_public')->where('id', $userId)
            ->first()['is_public'] ?? false;

        if ($isPublic) {
            return true;
        } else {
            return false;
        }
    }

    private function loadCommonData($name, $lastName, $userId)
    {
        $data['title']        = 'Cover of ' . $name;
        $data['filters']      = $this->newsSourcesModel->getDistinctCategoriesByUserId($userId);;
        $data['userCover']    = $name . ' ' . $lastName;
        $data['nameUser']     = $name;
        $data['lastNameUser'] = $lastName;
        $data['userId']       = $userId;
        $data['largeTitle']   = $name . ' Unique News Cover';

        return $data;
    }
}
