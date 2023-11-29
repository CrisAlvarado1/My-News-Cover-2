<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class News extends BaseController
{
    public function index()
    {
        // Hay que enviar las noticias y los filtros
        $data['title']      = 'My Cover';
        $content            = view('users/news/index', $data);

        return parent::renderTemplate($content, $data);
    }
}
