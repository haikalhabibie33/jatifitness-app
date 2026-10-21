<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PageSettingService;

class BasicInformationController extends BaseController
{
    private PageSettingService $pageSettingService;

    public function __construct()
    {
        $this->pageSettingService = new PageSettingService();
    }
    
    public function index(): string
    {
        $home = $this->pageSettingService->getPage('home');
        $about = $this->pageSettingService->getPage('about');
        $contact = $this->pageSettingService->getPage('contact');
        $services = $this->pageSettingService->getPage('services');
        $quote = $this->pageSettingService->getPage('quote');

        return view('pages/admin/basicinformation', [
            'active' => 'basicinformation',
            'home' => $home,
            'about' => $about,
            'contact' => $contact,
            'services' => $services,
            'quote' => $quote
        ]);
    }

    public function update() {
        // $ps   = new PageSettingsModel();
        $post = $this->request->getPost();

        // --- HOME ---
        $home = $this->pageSettingService->getPage('home');
        $home['home_logo_url']     = $post['home_logo_url']     ?? $home['home_logo_url']     ?? null;
        $home['home_owner_name']   = $post['home_owner_name']   ?? $home['home_owner_name']   ?? null;
        $home['home_hero_caption'] = $post['home_hero_caption'] ?? $home['home_hero_caption'] ?? null;
        $home['home_youtube_url']  = $post['home_youtube_url']  ?? $home['home_youtube_url']  ?? null;

        $this->pageSettingService->updateWhere('home', $home);

        //QUOTE
        $quote = $this->pageSettingService->getPage('quote');
        $quote['quote_section_title'] = $post['quote_section_title'] ?? $quote['quote_section_title'] ?? null;
        $quote['quote_section_description'] = $post['quote_section_description'] ?? $quote['quote_section_description'] ?? null;

        $this->pageSettingService->updateWhere('quote', $quote);

        // --- ABOUT ---
        $about = $this->pageSettingService->getPage('about');
        $about['about_hero_url']              = $post['about_hero_url']              ?? $about['about_hero_url']              ?? null;
        $about['about_section_title']         = $post['about_section_title']         ?? $about['about_section_title']         ?? null;
        $about['about_hero_caption']          = $post['about_hero_caption']          ?? $about['about_hero_caption']          ?? null;
        $about['about_description_primary']   = $post['about_description_primary']   ?? $about['about_description_primary']   ?? null;
        $about['about_description_secondary'] = $post['about_description_secondary'] ?? $about['about_description_secondary'] ?? null;

        $this->pageSettingService->updateWhere('about', $about);

        // --- CONTACT ---
        $contact = $this->pageSettingService->getPage('contact');
        $contact['contact_city']           = $post['contact_city']           ?? $contact['contact_city']           ?? null;
        $contact['contact_street']         = $post['contact_street']         ?? $contact['contact_street']         ?? null;
        $contact['contact_phone']          = $post['contact_phone']          ?? $contact['contact_phone']          ?? null;
        $contact['contact_available_time'] = $post['contact_available_time'] ?? $contact['contact_available_time'] ?? null;
        $contact['contact_email']          = $post['contact_email']          ?? $contact['contact_email']          ?? null;
        $contact['contact_emailMessage']          = $post['contact_emailMessage']          ?? $contact['contact_emailMessage']          ?? null;
        $contact['contact_facebook_url']   = $post['contact_facebook_url']   ?? $contact['contact_facebook_url']   ?? null;
        $contact['contact_instagram_url']  = $post['contact_instagram_url']  ?? $contact['contact_instagram_url']  ?? null;
        $contact['contact_tiktok_url']     = $post['contact_tiktok_url']     ?? $contact['contact_tiktok_url']     ?? null;

        $this->pageSettingService->updateWhere('contact', $contact);

        // --- SERVICES ---
        $services = $this->pageSettingService->getPage('services');
        $services['services_section_title'] = $post['services_section_title'] ?? $services['services_section_title'] ?? null;
        $services['services_hero_caption']  = $post['services_hero_caption']  ?? $services['services_hero_caption']  ?? null;
        $services['services_quality_equipment_desc']  = $post['services_quality_equipment_desc']  ?? $services['services_quality_equipment_desc']  ?? null;
        $services['services_health_caring_desc']  = $post['services_health_caring_desc']  ?? $services['services_health_caring_desc']  ?? null;
        $services['services_gym_strategies_desc']  = $post['services_gym_strategies_desc']  ?? $services['services_gym_strategies_desc']  ?? null;

        $this->pageSettingService->updateWhere('services', $services);

        return redirect()->back()->with(
            'success',
            'Basic information updated successfully.'
        );

    }

    public function resetAll()
    {
        $pages = ['home', 'about', 'contact', 'services'];
        // $model = new PageSettingsModel();

        foreach ($pages as $page) {
            $this->pageSettingService->resetPageFromJson($page);
        }

        return redirect()
            ->back()
            ->with('success', 'All basic information has been reset to default.');
    }

    
    public function reset(string $page)
    {
        $allowed = ['home', 'about', 'contact', 'services'];

        if (! in_array($page, $allowed, true)) {
            return redirect()
                ->back()
                ->with('error', 'Invalid page.');
        }

        // $model = new PageSettingsModel();
        $model = $this->pageSettingService->resetPageFromJson($page);

        if (!$model) {
            return redirect()
                ->back()
                ->with('error', 'Reset failed.');
        }

        return redirect()->back()->with(
            'success',
            ucfirst($page) . ' successfully reset to default.'
        );
    }

    
    public function updatePaymentSetting()
    {
        $raw = $this->request->getPost('online_payment_enabled');

        // ✅ normalize semua input jadi boolean
        $enabled = filter_var($raw, FILTER_VALIDATE_BOOLEAN);

        // ✅ simpan konsisten sebagai string
        $value = $enabled ? 'true' : 'false';
  
        $result = $this->pageSettingService->updateSetting('system','online_payment_enabled',$value);
        if (!$result) {
            return redirect()
                ->back()
                ->with('error', 'Settings failed updated!');
        }

        return redirect()
            ->back()
            ->with('success', 'Setting updated');
    }
}