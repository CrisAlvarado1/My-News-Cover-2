<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Display the index page in the app (Login).
     *  
     * @return string Rendered HTML content for the login.
     */
    public function index()
    {
        helper('form');
        $data['title'] = "Login";
        $content       = view('index');

        return parent::renderTemplate($content, $data);
    }
}
