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

    public function create()
    {
        $data['title']            = 'New Category';
        $data['actionTitle']      = 'Create Category';
        $content                  = view('admin/form', $data);

        return parent::renderTemplate($content, $data);
    }

    public function edit($id)
    {
        $categoryModel            = Model(CategoryModel::class);
        $data['title']            = 'Edit Categories';
        $data['actionTitle']      = 'Edit Category';
        $data['category']         = $categoryModel->where('id', $id)->first();;
        $content                  = view('admin/form', $data);

        return parent::renderTemplate($content, $data);
    }

    // insert / update data
    public function save()
    {
        $categoryModel = Model(CategoryModel::class);
        $id = $this->request->getVar('id');
        $data = [
            'name' => $this->request->getVar('name')
        ];
        if ($id) {
            $categoryModel->update($id, $data);
        } else {
            $categoryModel->insert($data);
        }
        return $this->response->redirect(site_url('admin/index'));
    }

    public function delete($id = null)
    {
        $categoryModel = model(CategoryModel::class);
        $categoryModel->where('id', $id)->delete();

        return $this->response->redirect(site_url('admin/index'));
    }
}
