<?php

namespace App\Controllers;

use App\Controllers\BaseNewsController;

class PublicCover extends BaseNewsController
{
    /**
     * Displays the main news page as the shared public front page.
     *
     * @param string $name     User's name.
     * @param string $lastName User's last name.
     * @param int    $userId   User's ID.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response View or redirection.
     */
    public function index($name, $lastName, $userId)
    {
        $data = $this->loadCommonData($name, $lastName, $userId);
        $data = parent::getAllNews($data, $userId);

        if ($this->validateIsPublic($userId)) {
            return parent::renderNewsPage($data);
        } else {
            return redirect()->to('/')->with('error', 'Invalid URL for access to public cover');
        }
    }

    /**
     * Displays the main news page filter by category as the shared public front page.
     *
     * @param string $name       User's name.
     * @param string $lastName   User's last name.
     * @param int    $userId     User's ID.
     * @param int    $categoryId Specific news category
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response View or redirection.
     */
    public function newsByCategory($name, $lastName, $userId, $categoryId)
    {
        $data = $this->loadCommonData($name, $lastName, $userId);
        $data = parent::getNewsByCategory($data, $userId, $categoryId);

        if ($this->validateIsPublic($userId)) {
            return parent::renderNewsPage($data);
        } else {
            return redirect()->to('/')->with('error', 'Invalid URL for access to public cover');;
        }
    }

    /**
     * Validates if the user has permissions to access public news.
     *
     * @param int $userId User's ID.
     *
     * @return bool True if the user has permissions, false otherwise.
     */
    private function validateIsPublic($userId)
    {
        $isPublic           = $this->userModel->select('is_public')->where('id', $userId)
            ->first()['is_public'] ?? false;

        if ($isPublic) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Load common data used in various views.
     *
     * @param string $name     User's first name.
     * @param string $lastName User's last name.
     * @param int    $userId   User's ID.
     *
     * @return array An associative array containing common data elements.
     */
    private function loadCommonData($name, $lastName, $userId)
    {
        $data['title']         = 'Cover of ' . $name;
        $data['filters']       = $this->newsSourcesModel->getDistinctCategoriesByUserId($userId);
        $data['userCover']     = $name . ' ' . $lastName;
        $data['largeTitle']    = $name . ' Unique News Cover';
        $data['routeCategory'] = 'user/' . $name . '/' . $lastName . '/' . $userId;
        return $data;
    }
}
