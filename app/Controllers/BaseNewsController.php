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
     * @var int ID of the current user.
     */
    protected $userId;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->newsSourcesModel = model(NewsSourcesModel::class);
        $this->newsModel        = model(NewsModel::class);
        $this->userModel        = model(UserModel::class);
        $this->newsTagsModel    = model(NewsTagsModel::class);
        $this->userId           = session()->get('user_id') ?? null;
    }

    /**
     * Send to render the index page of the news area
     * 
     * @return string Rendered HTML content for the admin index page. 
     */
    public function renderNewsPage($data)
    {
        $content = view('users/news/index', $data);
        return parent::renderTemplate($content, $data);
    }

    /**
     * Retrieve all relationed news by unique identificator user.
     * 
     * @param array $data An associative array containing additional data.
     * @param int   $userId The unique identifier of the user.
     * 
     * @return array A associative array of different data and with the all news.
     */
    public function getAllNews($data, $userId)
    {
        $data['allNews'] = $this->newsModel->getNews($userId);
        return $data;
    }

    /**
     * Retrieve all news related to a unique user and category identifier.
     *
     * @param array $data An associative array containing additional data.
     * @param int   $userId The unique identifier of the user.
     * @param int   $categoryId The unique identifier of the category.
     * 
     * @return array An associative array with various data and all related news.
     */
    public function getNewsByCategory($data, $userId, $categoryId)
    {
        $data['allNews']    = $this->newsModel->getNews($userId, $categoryId);
        $data['categoryId'] = $categoryId;
        return $data;
    }
}
