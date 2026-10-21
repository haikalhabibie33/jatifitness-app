<div class="admin-wrapper d-flex">
  <!-- Sidebar -->
  <aside class="admin-sidebar bg-dark text-white p-3" id="sidebar">
    <div class="mb-4 small text-uppercase text-secondary">Menu</div>
    <nav class="nav nav-pills flex-column gap-1">
      
      <a class="nav-link <?= (($active ?? '') === 'dashboard') 
         ? 'active' : '' ?>" 
         href="<?= site_url('admin/dashboard') ?>"
         data-testid="dashboard-menu">
         <i class="bi bi-speedometer2 me-2"></i> Dashboard
      </a>

      <div class="nav-item">
         <div class="text-white-50 small mt-3 mb-1">Activities</div>

         <a class="nav-link <?= (($active ?? '')==='checkin') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/checkin') ?>"
           data-testid="checkin-menu">
           <i class="bi bi-qr-code-scan me-2"></i> Check-In
        </a>

         <a class="nav-link <?= (($active ?? '') === 'checkin-list')
            ? 'active' : '' ?>" 
            href="<?= site_url('admin/checkin-list') ?>">
            <i class="bi bi-clock-history me-2"></i> History
         </a>

         <a class="nav-link <?= (($active ?? '') === 'schedule') ? 'active' : '' ?>" 
            href="<?= site_url('admin/schedule') ?>">
            <i class="bi bi-calendar-day me-2"></i> Schedule
         </a>
      </div>

      <div class="nav-item">
        <div class="text-white-50 small mt-3 mb-1">Transaction</div>

        <a class="nav-link <?= (($active ?? '')==='payment') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/payment') ?>"
           data-testid="payment-menu">
           <i class="bi bi-cash-stack me-2"></i> 
           Payment
        </a>

        <a class="nav-link <?= (($active ?? '')==='refund') ?'active':'' ?>" 
           href="<?= site_url('admin/refund') ?>">
           <i class="bi bi-arrow-clockwise me-2"></i> Refund
       </a>

       <a class="nav-link <?= (($active ?? '')==='transaction') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/transaction') ?>"
           data-testid="transaction-menu">
           <i class="bi bi-cash-stack me-2"></i> Report
        </a>

      </div>
      
      <div class="nav-item">
        <div class="text-white-50 small mt-3 mb-1">Membership</div>

        <a class="nav-link <?= (($active ?? '')==='memberRequest') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/membership-request') ?>"
           data-testid="request-menu">
           <i class="bi bi-people-fill me-2"></i> Request
        </a>

        <a class="nav-link <?= (($active ?? '')==='member') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/member') ?>"
           data-testid="data-menu">
           <i class="bi bi-people-fill me-2"></i> Data
        </a>

        <a class="nav-link <?= (($active ?? '')==='membershipPackage') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/membership-package') ?>"
           data-testid="plans-menu">
           <i class="bi bi-box-seam me-2"></i> Plans
        </a>

      </div>

      <div class="nav-item">
        <div class="text-white-50 small mt-3 mb-1">Contact</div>

        <a class="nav-link <?= (($active ?? '')==='contact') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/contact') ?>"
           data-testid="contact-menu">
           <i class="bi bi-person-rolodex me-2"></i> Message
        </a>
      </div>

      <div class="nav-item">
        <div class="text-white-50 small mt-3 mb-1">Configuration</div>

        <a class="nav-link <?= (($active ?? '')==='basicinformation') 
        ? 'active':'' ?>" 
        href="<?= site_url('admin/basicinformation') ?>"
        data-testid="basicinformation-menu">
        <i class="bi bi-signal me-2"></i> Settings
        </a>

        <a class="nav-link <?= (($active ?? '')==='about') 
           ?'active':'' ?>" 
           href="<?= site_url('admin/about') ?>"
           data-testid="about-menu">
           <i class="bi bi-people me-2"></i> About
        </a>
      </div>

    </nav>
  </aside>

  <!-- Backdrop untuk mobile -->
  <div class="admin-backdrop" id="sidebarBackdrop"></div>