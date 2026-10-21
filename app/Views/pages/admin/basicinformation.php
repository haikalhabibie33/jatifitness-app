<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<div class="container py-4">

<!-- ================= HEADER + RESET ================= -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Basic Information Data Management</h2>

    <form method="post"
          action="<?= site_url('admin/basicinformation/reset') ?>"
          onsubmit="return confirm('Reset ALL basic information to default? This cannot be undone.')">
        <?= csrf_field() ?>
        <button class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-1"></i>
            Reset to Default
        </button>
    </form>
</div>

<form action="<?= site_url('admin/basicinformation/update') ?>" method="post">
<?= csrf_field() ?>

<!-- ================= HOME ================= -->
<div class="card shadow-sm mb-4 collapsible-section active">
    <div class="card-header bg-dark text-white py-3 collapsible-header">
        <h5 class="mb-0 fw-semibold d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-house-fill me-2"></i> Home
            </span>
            <i class="bi bi-chevron-down arrow"></i>
        </h5>
    </div>

    <div class="card-body collapsible-content">
        <div class="collapsible-inner">

        <?= view('components/page_preview', [
            'src' => 'previews/home/home-preview.png',
            'alt' => 'Home Page Layout Preview',
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Logo URL',
            'name'  => 'home_logo_url',
            'value' => $home['home_logo_url'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Owner Name',
            'name'  => 'home_owner_name',
            'value' => $home['home_owner_name'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Hero Caption',
            'name'  => 'home_hero_caption',
            'value' => $home['home_hero_caption'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Youtube URL',
            'name'  => 'home_youtube_url',
            'value' => $home['home_youtube_url'] ?? null,
        ]) ?>

        <?= view('components/page_preview', [
            'src' => 'previews/home/home-preview-2.png',
            'alt' => 'Home Page Layout Preview',
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Quote Section Title',
            'name'  => 'quote_section_title',
            'value' => $quote['quote_section_title'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Quote Section Description',
            'name'  => 'quote_section_description',
            'value' => $quote['quote_section_description'] ?? null,
        ]) ?>
        </div>
    </div>
</div>

<!-- ================= ABOUT ================= -->
<div class="card shadow-sm mb-4 collapsible-section">
    <div class="card-header bg-dark text-white py-3 collapsible-header">
        <h5 class="mb-0 fw-semibold d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-info-circle-fill me-2"></i> About
            </span>
            <i class="bi bi-chevron-down arrow"></i>
        </h5>
    </div>

    <div class="card-body collapsible-content">
         <div class="collapsible-inner">
        <?= view('components/page_preview', [
            'src' => 'previews/about/about-preview.png',
            'alt' => 'About Page Layout Preview',
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Hero Image URL',
            'name'  => 'about_hero_url',
            'value' => $about['about_hero_url'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Section Title',
            'name'  => 'about_section_title',
            'value' => $about['about_section_title'] ?? null,
        ]) ?>

        <?= view('components/input_field', [
            'label' => 'Hero Caption',
            'name'  => 'about_hero_caption',
            'value' => $about['about_hero_caption'] ?? null,
        ]) ?>

        <?= view('components/input_text_area', [
            'label' => 'Primary Description',
            'name'  => 'about_description_primary',
            'value' => $about['about_description_primary'] ?? null,
        ]) ?>

        <?= view('components/input_text_area', [
            'label' => 'Secondary Description',
            'name'  => 'about_description_secondary',
            'value' => $about['about_description_secondary'] ?? null,
        ]) ?>
    </div>
</div>

<!-- ================= CONTACT ================= -->
<div class="card shadow-sm mb-4 collapsible-section">
    <div class="card-header bg-dark text-white py-3 collapsible-header">
        <h5 class="mb-0 fw-semibold d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-telephone-fill me-2"></i> Contact
            </span>
            <i class="bi bi-chevron-down arrow"></i>
        </h5>
    </div>

    <div class="card-body collapsible-content">
         <div class="collapsible-inner">
            <?= view('components/page_preview', [
                'src' => 'previews/contact/contact-preview.png',
                'alt' => 'Contact Page Layout Preview',
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'City / Town',
                'name'  => 'contact_city',
                'value' => $contact['contact_city'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Street Address',
                'name'  => 'contact_street',
                'value' => $contact['contact_street'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Phone Number',
                'name'  => 'contact_phone',
                'value' => $contact['contact_phone'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Available Time',
                'name'  => 'contact_availableToPhone',
                'value' => $contact['contact_availableToPhone'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Email',
                'name'  => 'contact_email',
                'value' => $contact['contact_email'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Email Message',
                'name'  => 'contact_emailMessage',
                'value' => $contact['contact_emailMessage'] ?? null,
            ]) ?>

            <?= view('components/page_preview', [
                'src' => 'previews/contact/contact-preview-2.png',
                'alt' => 'Contact Page Layout Preview',
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Facebook URL',
                'name'  => 'contact_facebook_url',
                'value' => $contact['contact_facebook_url'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Instagram URL',
                'name'  => 'contact_instagram_url',
                'value' => $contact['contact_instagram_url'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'TikTok URL',
                'name'  => 'contact_tiktok_url',
                'value' => $contact['contact_tiktok_url'] ?? null,
            ]) ?>
        </div>
    </div>
</div>

<!-- ================= SERVICES ================= -->
<div class="card shadow-sm mb-4 collapsible-section">
    <div class="card-header bg-dark text-white py-3 collapsible-header">
        <h5 class="mb-0 fw-semibold d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-briefcase-fill me-2"></i> Services
            </span>
            <i class="bi bi-chevron-down arrow"></i>
        </h5>
    </div>

    <div class="card-body collapsible-content">
         <div class="collapsible-inner">
            <?= view('components/page_preview', [
                'src' => 'previews/services/service-preview.png',
                'alt' => 'Services Page Layout Preview',
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Section Title',
                'name'  => 'services_section_title',
                'value' => $services['services_section_title'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Hero Caption',
                'name'  => 'services_hero_caption',
                'value' => $services['services_hero_caption'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Quality Equipment Description',
                'name'  => 'services_quality_equipment_desc',
                'value' => $services['services_quality_equipment_desc'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Health Caring Description',
                'name'  => 'services_health_caring_desc',
                'value' => $services['services_health_caring_desc'] ?? null,
            ]) ?>

            <?= view('components/input_field', [
                'label' => 'Gym Strategies Description',
                'name'  => 'services_gym_strategies_desc',
                'value' => $services['services_gym_strategies_desc'] ?? null,
            ]) ?>
        </div>
    </div>

<!-- ================= SUBMIT ================= -->
<div class="text-end mt-4">
    <button class="btn btn-danger px-4 py-2 fw-semibold">
        <i class="bi bi-check2-circle me-2"></i>
        Save Changes
    </button>
</div>

</form>
</div>

<?= $this->endSection() ?>
``