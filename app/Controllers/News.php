<?php

namespace App\Controllers;

use App\Controllers\BaseNewsController;
use App\Models\UserModel;

class News extends BaseNewsController
{
    /**
     * Displays the main news page.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response View or redirection.
     */
    public function index()
    {
        $data = $this->loadCommonData();
        $data = parent::getAllNews($data, $this->userId);

        if ($this->validateExistsNewsSources($data['filters'])) {
            return parent::renderNewsPage($data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }

    /**
     * Displays the main news page filter by category
     * 
     * @param int   $categoryId The unique identifier of the category.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response View or redirection.
     */
    public function filterNewsByCategory($categoryId)
    {
        $data = $this->loadCommonData($categoryId);
        $data = parent::getNewsByCategory($data, $this->userId, $categoryId);

        if ($this->validateExistsNewsSources($data['filters'])) {
            return parent::renderNewsPage($data);
        } else {
            return redirect()->to('users/newsSources/create');
        }
    }


    /**
     * Load common data used in various views in the news area.
     *
     * @param int $categoryId A specific category identificator.
     * 
     * @return array An associative array containing common data elements.
     */
    private function loadCommonData($categoryId = null)
    {
        $data['title']         = 'My Cover';
        $data['largeTitle']    = 'Your Unique News Cover';
        $data['filters']       = $this->newsSourcesModel->getDistinctCategoriesByUserId($this->userId);
        $data['routeCategory'] = 'users/news/index';
        $data['script']        = '<script src="' . base_url('js/filter.js') . '"></script>';

        if ($categoryId === null) {
            $data['tags'] = $this->newsTagsModel->getNewsTagsByUser($this->userId);
        } else {
            $data['tags']       = $this->newsTagsModel->getNewsTagsByUser($this->userId, $categoryId);
            $data['categoryId'] = $categoryId;
        }

        return $data;
    }

    /**
     * Searches for keywords in all news and renders the result.
     *
     * @return string Rendered template content with search results.
     */
    public function searchInAllNews()
    {
        $keywords = $this->request->getVar('keywords');
        $data     = $this->loadCommonData();
        $data     = parent::getAllNews($data, $this->userId);

        if (!empty($keywords)) {
            $data['allNews']      = $this->searchNewsWithKeywords($data, $keywords);
            $data['dataKeywords'] = $keywords;
        }
        return parent::renderNewsPage($data);
    }

    /**
     * Searches for keywords in news of a specific category and renders the result.
     *
     * @param int $categoryId The ID of the category to filter by.
     */
    public function searchInCategoryNews($categoryId)
    {
        $keywords = $this->request->getVar('keywords');
        $data     = $this->loadCommonData($categoryId);
        $data     = parent::getNewsByCategory($data, $this->userId, $categoryId);

        if (!empty($keywords)) {
            $data['allNews']      = $this->searchNewsWithKeywords($data, $keywords);
            $data['dataKeywords'] = $keywords;
        }
        return parent::renderNewsPage($data);
    }

    /**
     * Filters news based on keywords.
     *
     * @param array  $news     The array of news to filter.
     * @param string $keywords The keywords to search for.
     *
     * @return array The filtered news.
     */
    private function searchNewsWithKeywords($data, $keywords)
    {
        $filteredNews = array_filter($data['allNews'], function ($news) use ($keywords) {
            // Convert to lowercase for a case-insensitive comparison
            $lowerKeywords = strtolower($keywords);

            // Check if the title or content contains all the keywords
            $containsKeywords =
                stripos($news['title'], $lowerKeywords) !== false ||
                stripos($news['short_description'], $lowerKeywords) !== false;

            return $containsKeywords;
        });

        return $filteredNews;
    }

    /**
     * Filters all news by selected tags and renders the result.
     * 
     * If tags are selected, it loads common data, retrieves news based on the selected tags,
     * and renders the news page. Otherwise, it redirects to the default news index page.
     */
    public function filterNewsByTagsInAllNews()
    {
        $tagsSelected = $this->request->getPost('tagsNews');

        if (!empty($tagsSelected)) {
            $data                 = $this->loadCommonData();
            $data['tagsSelected'] = $tagsSelected;
            $data['allNews']      = $this->newsModel->getNewsByTags($tagsSelected, $this->userId);

            return parent::renderNewsPage($data);
        } else {
            return redirect()->to('users/news/index');
        }
    }

    /**
     * Filters news of a specific category by selected tags and renders the result.
     *
     * @param int $categoryId The ID of the category to filter by.
     * 
     * @return string Rendered template content or redirects back to filter news by category.
     */
    public function filterNewsByTagsInCategoryNews($categoryId)
    {
        $tagsSelected = $this->request->getPost('tagsNews');

        if (!empty($tagsSelected)) {
            $data                 = $this->loadCommonData($categoryId);
            $data['tagsSelected'] = $tagsSelected;
            $data['allNews']      = $this->newsModel->getNewsByTags($tagsSelected, $this->userId, $categoryId);

            return parent::renderNewsPage($data);
        } else {
            $this->filterNewsByCategory($categoryId);
        }
    }

    /**
     * Validates if news exists.
     *
     * @param array $news The array of news to validate.
     *
     * @return bool True if news exists, false otherwise.
     */
    private function validateExistsNewsSources($news)
    {
        return !empty($news);
    }

    /**
     * Displays the public cover page, including the option to make it public and the access link.
     *
     * @return string Rendered template content.
     */
    public function publicCover()
    {
        $userModel        = Model(UserModel::class);
        $data['title']    = "Public Cover";
        $data['isPublic'] = $userModel->select('is_public')->where('id', $this->userId)->first()['is_public'] ?? false;
        if ($data['isPublic']) {
            $data['accessLink'] = $this->buildPublicCoverLink();
            $data['script']   = '<script src="' . base_url('js/copyToClipboard.js') . '"></script>';
        }
        $content = view('users/news/public', $data);
        return parent::renderTemplate($content, $data);
    }

    /**
     * Handles the user's request to make their cover public or private.
     * Updates the user's public status and provides an access link if made public.
     *
     * @return string Rendered template content.
     */
    public function makePublicCover()
    {
        $userModel        = Model(UserModel::class);
        $data['title']    = "Public Cover";
        $confirm          = $this->request->getPost('confirm');
        $disconfirm       = $this->request->getPost('disconfirm');

        if (!empty($confirm) && empty($disconfirm)) {
            $userModel->where('id', $this->userId)->set(['is_public' => true])->update();
            $data['accessLink'] = $this->buildPublicCoverLink();
            $data['script']   = '<script src="' . base_url('js/copyToClipboard.js') . '"></script>';
        } else {
            $userModel->where('id', $this->userId)->set(['is_public' => false])->update();
        }

        $data['isPublic'] = $userModel->select('is_public')->where('id', $this->userId)->first()['is_public'] ?? false;
        $content          = view('users/news/public', $data);
        return parent::renderTemplate($content, $data);
    }

    /**
     * Builds and returns the access link for the public cover based on the user's session information.
     *
     * @return string The access link for the public cover.
     */
    private function buildPublicCoverLink()
    {
        $nameUser     = session()->get('name');
        $lastNameUser = session()->get('lastName');
        $link         = 'http://mynewscover2.com/user/' .
            $nameUser . '/' . $lastNameUser . '/' . $this->userId . '';
        return $link;
    }
}
