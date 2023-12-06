<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;

class NewsSources extends BaseController
{

    protected $filters = ['auth'];

    public function index()
    {
        $newsSourcesModel    = Model(NewsSourcesModel::class);
        $data['title']       = 'News Sources';
        $userId              = session()->get('user_id');
        $data['newsSources'] = $newsSourcesModel->getNewsSourcesByUserId($userId);

        if ($this->validateExistsNewsSources($data['newsSources'])) {
            $content = view('users/newsSources/index', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }

    public function create()
    {
        $categoryModel       = Model(CategoryModel::class);
        $data['title']       = 'New - News Sources';
        $data['actionTitle'] = 'Create News Sources';
        $data['categories']  = $categoryModel->findAll();
        $content             = view('users/newsSources/form', $data);

        return parent::renderTemplate($content, $data);
    }

    public function edit($id)
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $categoryModel    = Model(CategoryModel::class);

        $data['title']       = 'Edit - News Sources';
        $data['actionTitle'] = 'Edit News Sources';
        $data['categories']  = $categoryModel->findAll();
        $data['newsSource']  = $newsSourcesModel->where('id', $id)->first();

        if ($this->validateExistsNewsSources($data['newsSource'])) {
            $content = view('users/newsSources/form', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }

    // insert / update data
    public function save()
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $id = $this->request->getVar('id');
        $data = [
            'name'        => $this->request->getVar('name'),
            'url'         => $this->request->getVar('url'),
            'category_id' => $this->request->getVar('category'),
            'user_id'     => session()->get('user_id')
        ];
        if ($id) {
            $newsSourcesModel->update($id, $data);
        } else {
            $newsSourcesModel->insert($data);
        }
        return $this->response->redirect(site_url('users/newsSources/index'));
    }

    public function delete($id = null)
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $newsModel = Model(NewsModel::class);

        // First delete the relational news
        $newsModel->where('news_source_id', $id)->delete();
        $newsSourcesModel->where('id', $id)->delete();

        return $this->response->redirect(site_url('users/newsSources/index'));
    }

    private function validateExistsNewsSources($newsSources)
    {
        if (!empty($newsSources)) {
            return true;
        } else {
            return false;
        }
    }
}
