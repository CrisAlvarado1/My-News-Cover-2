<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\NewsModel;
use App\Models\NewsSourcesModel;

class NewsSources extends BaseController
{
    /**
     * Displays the list of news sources associated with the current user.
     * Redirects to create page if no news sources exist.
     *
     * @return string Rendered template content.
     */
    public function index()
    {
        $newsSourcesModel    = Model(NewsSourcesModel::class);
        $data['title']       = 'News Sources';
        $userId              = session()->get('user_id');
        $data['newsSources'] = $newsSourcesModel->getNewsSourcesByUserId($userId);
        $data['script']      = '<script src="' . base_url('js/confirmDelete.js') . '"></script>';

        if ($this->validateExistsNewsSources($data['newsSources'])) {
            $content = view('users/newsSources/index', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }

    /**
     * Displays the form to create a new news source.
     *
     * @return string Rendered template content.
     */
    public function create()
    {
        helper('form');
        $categoryModel       = Model(CategoryModel::class);
        $data['title']       = 'New - News Sources';
        $data['actionTitle'] = 'Create News Sources';
        $data['categories']  = $categoryModel->findAll();
        $content             = view('users/newsSources/form', $data);
        $data['script']      = '<script src="' . base_url('js/validateNewsSources.js') . '"></script>';

        return parent::renderTemplate($content, $data);
    }

    /**
     * Displays the form to edit an existing news source.
     * Redirects to create page if the news source does not exist.
     *
     * @param int $id The ID of the news source to edit.
     * @return string Rendered template content.
     */
    public function edit($id)
    {
        helper('form');
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $categoryModel    = Model(CategoryModel::class);

        $data['title']       = 'Edit - News Sources';
        $data['actionTitle'] = 'Edit News Sources';
        $data['categories']  = $categoryModel->findAll();
        $data['newsSource']  = $newsSourcesModel->where('id', $id)->first();
        $data['script']      = '<script src="' . base_url('js/validateNewsSources.js') . '"></script>';

        if ($this->validateExistsNewsSources($data['newsSource'])) {
            $content = view('users/newsSources/form', $data);
            return parent::renderTemplate($content, $data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }

    /**
     * Saves or updates a news source based on the provided form data.
     *
     * @return RedirectResponse Redirects to the news sources index page.
     */
    public function save()
    {
        // First validate the user input
        $validation       = \Config\Services::validation();
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $newsModel        = Model(NewsModel::class);
        $id               = $this->request->getVar('id');

        $data             = [
            'name'        => $this->request->getVar('name'),
            'url'         => $this->request->getVar('url'),
            'category_id' => $this->request->getVar('category'),
            'user_id'     => session()->get('user_id')
        ];

        $validation->setRules([
            'name'     => 'required|max_length[255]',
            'url'      => 'required|valid_url|max_length[255]',
            'category' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $route = (!empty($id)) ? 'users/newsSources/edit/' . $id : 'users/newsSources/create';
            return redirect()->to($route)->withInput()->with('errors', $validation->getErrors());
        }

        // Later, identification if has $id is an update, if not have a id it´s a insert
        if ($id) {
            $newsSourcesModel->update($id, $data);
            $newsModel->set('category_id', $data['category_id'])->where('news_source_id', $id)->update();
        } else {
            $newsSourcesModel->insert($data);
        }
        return $this->response->redirect(site_url('users/newsSources/index'));
    }

    /**
     * Deletes a news source and its associated news.
     *
     * @param int|null $id The ID of the news source to delete.
     * @return RedirectResponse Redirects to the news sources index page.
     */
    public function delete($id = null)
    {
        $newsSourcesModel = Model(NewsSourcesModel::class);
        $newsModel = Model(NewsModel::class);

        // First delete the relational news
        $newsModel->where('news_source_id', $id)->delete();
        // Later delete the specif news sources
        $newsSourcesModel->where('id', $id)->delete();

        return $this->response->redirect(site_url('users/newsSources/index'));
    }

    /**
     * Validates the existence of news sources.
     *
     * @param mixed $newsSources The news sources data.
     * @return bool Returns true if news sources exist, false otherwise.
     */
    private function validateExistsNewsSources($newsSources)
    {
        return !empty($newsSources);
    }
}
