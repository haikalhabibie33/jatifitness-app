<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ContactService;

class ContactController extends BaseController
{
    private ContactService $contactService;

    public function __construct()
    {
        $this->contactService = new ContactService();
    }

    public function getContact() : string {
        $contacts = $this->contactService->findAll();

        return view('pages/admin/contact-message/index', [
            'active' => 'contact',
            'contacts' => $contacts['data']
        ]);
        
    }

    public function deleteContact(int $id) {
        $result = $this->contactService->delete($id);
        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }
}