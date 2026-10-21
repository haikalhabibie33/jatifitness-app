<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PageSettingsModel;

class AboutController extends BaseController
{
    public function index(): string
    {
        $ps = new PageSettingsModel();
        $about = $ps->getPage('about');

        return view('pages/web/about', [
            'about' => $about
        ]);
    }
}
