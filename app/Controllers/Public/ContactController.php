<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PageSettingsModel;
use App\Models\ContactModel;

class ContactController extends BaseController
{
    public function index(): string
    {
        $ps = new PageSettingsModel();
        $contact = $ps->getPage('contact');

        return view('pages/web/contact', [
            'contact' => $contact
        ]);
    }

    
    public function submit()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name'    => 'required|min_length[3]|max_length[30]',
            'email'   => 'required|valid_email|max_length[255]',
            'subject' => 'required|min_length[3]',
            'message' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            // balik ke halaman contact + error
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $contactModel = new ContactModel();

        $data = [
            'name'    => trim($this->request->getPost('name')),
            'email'   => trim($this->request->getPost('email')),
            'subject' => trim($this->request->getPost('subject')),
            'message' => trim($this->request->getPost('message')),
        ];

        try {
            $contactModel->insert($data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to send message. Please try again.');
        }

        return redirect()
            ->to('/contact')
            ->with('success', 'Your message has been sent successfully!');
    }

}
