<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Filters\AuthFilter;
use App\Models\NewsModel;

class Category extends BaseController
{
    protected $filters = ['auth'];

    /**
     * Display the index page for managing categories in the admin area.
     *  
     * @return string Rendered HTML content for the admin index page.
     */
    public function index()
    {
        $categoryModel      = Model(CategoryModel::class);
        $data['title']      = 'Categories';
        $data['categories'] = $categoryModel->findAll();
        $content            = view('admin/index', $data);

        return parent::renderTemplate($content, $data);
    }

    /**
     * Display the create category page in the admin area.
     *  
     * @return string Rendered HTML content for the create category page.
     */
    public function create()
    {
        $data['title']            = 'New Category';
        $data['actionTitle']      = 'Create Category';
        $content                  = view('admin/form', $data);

        return parent::renderTemplate($content, $data);
    }

    /**
     * Display the edit a especific category page in the admin area.
     * 
     * @param int $id A specific id of the category
     *
     * @return string Rendered HTML content for the create category page.
     */
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

    /**
     * Delete a specific category without news relationed.
     * 
     * @param int $id A specific id of the category
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response redirection.
     */
    public function delete($id = null)
    {
        $newsModel     = model(NewsModel::class);
        $categoryModel = model(CategoryModel::class);

        $relatedNews = $newsModel->where('category_id', $id)->countAllResults();
        if ($relatedNews == 0) {
            $categoryModel->where('id', $id)->delete();
        }

        return $this->response->redirect(site_url('admin/index'));
    }
}
