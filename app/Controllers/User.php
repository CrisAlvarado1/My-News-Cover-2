<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\CountryModel;
use App\Models\RoleModel;
use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        $countryModel  = model(CountryModel::class);
        $roleModel     = model(RoleModel::class);

        $data['title']     = "Sign Up";
        $data['countries'] = $countryModel->findAll();
        $data['role']      = $roleModel->where('name', 'user')->first();

        helper('form');
        return view('templates/navBar', $data)
            . view('users/index', $data)
            . view('templates/footer');
    }

    public function store($roleId)
    {
        $userModel    = model(UserModel::class);
        $addressModel = model(AddressModel::class);

        $userData = [
            'email'        => $this->request->getVar('email'),
            'first_name'   => $this->request->getVar('lastName'),
            'last_name'    => $this->request->getVar('email'),
            'role_id'      => $roleId,
            'password'     => $this->request->getVar('password'),
            'phone_number' => $this->request->getVar('phone')
        ];

        if (!$userModel->isEmailExists($userData['email'])) {
            $userId = $userModel->insert($userData);

            if ($userId) {
                $addressData = [
                    'user_id'     => $userId,
                    'address1'    => $this->request->getVar('address1'),
                    'address2'    => $this->request->getVar('address2'),
                    'country_id'  => $this->request->getVar('countryId'),
                    'city'        => $this->request->getVar('city'),
                    'postal_code' => $this->request->getVar('postalCode')
                ];
                $addressModel->insert($addressData);

                $this->sendEmailToUser($userData['email']);

                return redirect()->to('/?status=register');
            } else {
                return redirect()->to('users/signup?error=true');
            }
        } else {
            return redirect()->to('/?error=exists');
        }
    }

    private function sendEmailToUser($userEmail)
    {
        $to_email = $userEmail;
        $subject  = "Account Verification - My News Cover";
        $body     = "Hello,\n\nThank you for registering with My News Cover. This email is to confirm that your account has been successfully created. You can now start enjoying our services.\n\nIf you have any questions or need assistance, feel free to contact our support team.\n\nBest regards,\nThe My News Cover Team";
        $headers  = "De: sender\'s email";

        mail($to_email, $subject, $body, $headers);
    }

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

        $this->setSession($user);
        if ($userModel->isAdmin($user['id'])) {
            // return redirect()->to('/'); // dirección a categorias
            echo "Admiiiin";
        } else {
            // return redirect()->to('/'); // dirección a dashboard
            echo "Usuarioooooooooo";
        }
    }

    private function setSession($user)
    {
        $role = ($user['role_id'] == 1) ? 'admin' : 'user';

        $session = session();
        $session->set([
            'user_id' => $user['id'],
            'email'   => $user['email'],
            'role'    => $role,
            'name'    => $user['first_name']
        ]);
    }
}
