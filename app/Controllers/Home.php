<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        helper('form');
        $data['title'] = "Login";
        return view('templates/navBar', $data)
            . view('index')
            . view('templates/footer');
    }
}
