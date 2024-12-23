<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\CountryModel;
use App\Models\RoleModel;
use App\Models\UserModel;

class User extends BaseController
{

    /**
     * Displays the sign-up form with the necessary data (countries and default user role).
     *
     * @return string Rendered template content.
     */
    public function index()
    {
        helper('form');
        $countryModel  = model(CountryModel::class);
        $roleModel     = model(RoleModel::class);

        $data['title']     = "Sign Up";
        $data['countries'] = $countryModel->findAll();
        $data['role']      = $roleModel->where('name', 'user')->first();
        $content           = view('users/index', $data);

        return parent::renderTemplate($content, $data);
    }

    /**
     * Handles the user registration process and sends a verification email.
     *
     * @param int $roleId The ID of the default user role.
     * @return RedirectResponse Redirects to the appropriate page based on the registration status.
     */
    public function store($roleId)
    {
        // First validate the important fields
        $validation    = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('users/index')->withInput()->with('error', $validation->getErrors());
        }

        $userModel    = model(UserModel::class);
        $addressModel = model(AddressModel::class);

        $userData = [
            'email'        => $this->request->getVar('email'),
            'first_name'   => $this->request->getVar('firstName'),
            'last_name'    => $this->request->getVar('lastName'),
            'role_id'      => $roleId,
            'password'     => $this->request->getVar('password'),
            'phone_number' => $this->request->getVar('phone')
        ];

        // Validate if the email already exist or not
        if (!$userModel->isEmailExists($userData['email'])) {
            // Insert the user data
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
                // Insert de address data relationated with the user
                $addressModel->insert($addressData);
                // Send the email to confirm the user
                $this->sendEmailToUser($userData['email']);
                return redirect()->to('/')->with('error', 'Successfully registered');
            } else {
                return redirect()->to('users/index')->with('error', 'Database problems, try again');
            }
        } else {
            return redirect()->to('/')->with('error', 'Already registered email');
        }
    }

    /**
     * Sends a registration confirmation email to the user.
     *
     * @param string $userEmail The email address of the registered user.
     * @return void
     */
    private function sendEmailToUser($userEmail)
    {
        $to_email = $userEmail;
        $subject  = "Account Verification - My News Cover";
        $body     = "Hello,\n\nThank you for registering with My News Cover. This email is to confirm that your account has been successfully created. You can now start enjoying our services.\n\nIf you have any questions or need assistance, feel free to contact our support team.\n\nBest regards,\nThe My News Cover Team";
        $headers  = "De: sender\'s email";

        mail($to_email, $subject, $body, $headers);
    }
}
