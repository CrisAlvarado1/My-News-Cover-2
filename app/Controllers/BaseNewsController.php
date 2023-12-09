<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;
use App\Models\NewsTagsModel;
use App\Models\UserModel;

class BaseNewsController extends BaseController
{
    /**
     * @var NewsModel News Sources Model.
     */
    public $newsSourcesModel;

    /**
     * @var NewsModel News Model.
     */
    public $newsModel;

    /**
     * @var NewsModel User Model.
     */
    public $userModel;

    /**
     * @var NewsTagsModel News Tags Model.
     */
    public $newsTagsModel;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->newsSourcesModel = model(NewsSourcesModel::class);
        $this->newsModel        = model(NewsModel::class);
        $this->userModel        = model(UserModel::class);
        $this->newsTagsModel    = Model(NewsTagsModel::class);
    }

    public function loadTags($data, $userId, $categoryId = null)
    {
        if ($categoryId === null) {
            $data['tags'] = $this->newsTagsModel->getNewsTagsByUser($userId);
        } else {
            $data['tags'] = $this->newsTagsModel->getNewsTagsByUser($userId, $categoryId);
        }

        return $data;
    }

    public function renderNews($data)
    {
        $content = view('users/news/index', $data);
        return parent::renderTemplate($content, $data);
    }
}
