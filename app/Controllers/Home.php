<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        helper('form');
        $data['title'] = "Login";
        $content       = view('index');

        return parent::renderTemplate($content, $data);
    }
}
