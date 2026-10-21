<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PageSettingsModel;
use App\Models\QuoteModel;

class HomeController extends BaseController
{
    public function index()
    {
        $ps = new PageSettingsModel();
        $home = $ps->getPage('home');
        $quoteHeader = $ps->getPage('quote');

        $quoteModel = new QuoteModel();
        $quoteList = $quoteModel->findAll(); // Assuming you want all quotes

        return view('pages/web/home', [
            'home' => $home,
            'quoteHeader' => $quoteHeader,
            'quoteList' => $quoteList
        ]);
    }
}
