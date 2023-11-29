<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Filters\AuthFilter;

class Category extends BaseController
{
    protected $filters = ['auth'];

    public function index()
    {
        $categoryModel      = Model(CategoryModel::class);
        $data['title']      = 'Categories';
        $data['categories'] = $categoryModel->findAll();
        $content            = view('admin/index', $data);

        return parent::renderTemplate($content, $data);
    }
}
