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
        $data['session']    = session();
        
        return view('templates/navBar', $data)
            . view('admin/index', $data)
            . view('templates/footer');
    }
}
