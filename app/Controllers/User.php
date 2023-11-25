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
        $roleModel = model(RoleModel::class);

        $data['title'] = "Sign Up";
        $data['countries'] = $countryModel->findAll();
        $data['role'] = $roleModel->where('name', 'user')->first();

        helper('form');
        return view('templates/navBar', $data)
            . view('users/signup', $data)
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
            return redirect()->to('/');
        } else {
            return redirect()->to('users/signup');
        }
    }
}
