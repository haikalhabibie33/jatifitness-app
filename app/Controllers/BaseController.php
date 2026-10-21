<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\PageSettingsModel;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        
        parent::initController($request, $response, $logger);
        $home = null;

        try {
            $ps = new \App\Models\PageSettingsModel();
            $home = $ps->getPage('home');
            $contact = $ps->getPage('contact');
            service('renderer')->setVar('home', $home);
            service('renderer')->setVar('contact', $contact);
        } catch (\Throwable $e) {
            log_message('error', '[BaseController] DB skipped: ' . $e->getMessage());
        }

        if (is_array($home) && isset($home['home_logo_url'])) {
            service('renderer')->setVar('global_home_logo', $home['home_logo_url']);
        }

        // preload lain kalau perlu
    }

}
