<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class TestEmail extends BaseController
{
    public function index()
    {
        $email = service('email');

        $email->setTo('chie.dorie@gmail.com');
        $email->setSubject('Test SMTP Jati Fitness');
        $email->setMessage('Kalau ini nyampe, SMTP hosting ✅');

        if ($email->send()) {
            return 'EMAIL TERKIRIM ✅';
        }

        return $email->printDebugger(['headers', 'subject', 'body']);
    }
}