<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function authenticate()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/')->withInput()->with('errors', $validation->getErrors());
        }

        $userModel    = model(UserModel::class);
        $userEmail    = $this->request->getVar('email');
        $userPassword = $this->request->getVar('password');

        $user = $userModel->getByEmail($userEmail);

        if (!$user || $userPassword !== $user['password']) {
            return redirect()->to('/')->with('error', 'Invalid email or password');
        }

        $data['session'] = $this->setSession($user);
        if ($userModel->isAdmin($user['id'])) {
            return redirect()->to('admin/index');
        } else {
            return redirect()->to('users/news/index');
        }
    }

    private function setSession($user)
    {
        $role = ($user['role_id'] == 1) ? 'admin' : 'user';

        $session = session();
        $session->set([
            'user_id'   => $user['id'],
            'email'     => $user['email'],
            'role'      => $role,
            'name'      => $user['first_name'],
            'is_logged' => true
        ]);
        return $session;
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
