<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PageSettingsModel;

class ServicesController extends BaseController
{
    public function index(): string
    {
        $ps = new PageSettingsModel();
        $services = $ps->getPage('services');
        $contact = $ps->getPage('contact');
        
        return view('pages/web/services', [
            'services' => $services,
            'contact' => $contact,
        ]);
    }
}
