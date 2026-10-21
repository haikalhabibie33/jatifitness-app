<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">About</h2>
        <p class="text-muted mb-0">
            Information about this application
        </p>
    </div>

    <div class="row g-4 pb-4">

        <!-- ABOUT APPLICATION -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle-fill fs-3 text-primary me-3"></i>
                        <h5 class="mb-0 fw-semibold">About the Application</h5>
                    </div>

                    <p class="text-muted mb-0">
                        <strong>Gym App</strong> is a membership management application designed
                        to help gym administrators manage member data, track active and inactive
                        memberships, and monitor membership growth through an interactive dashboard.
                    </p>
                </div>
            </div>
        </div>

        <!-- KEY FEATURES -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-stars fs-3 text-warning me-3"></i>
                        <h5 class="mb-0 fw-semibold">Key Features</h5>
                    </div>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Membership management (Active & Inactive)
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Membership expiration tracking
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Dashboard analytics & growth chart
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Contact message management
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Centralized basic information management
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- TECHNOLOGY USED -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-cpu-fill fs-3 text-info me-3"></i>
                        <h5 class="mb-0 fw-semibold">Technology Used</h5>
                    </div>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Backend: <strong>PHP (CodeIgniter 4)</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Frontend: <strong>Bootstrap 5</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Charts & Visualization: <strong>Chart.js</strong>
                        </li>
                        <li>
                            <i class="bi bi-dot"></i> Database: <strong>MySQL / MariaDB</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- AUTHOR / FOOTER -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-badge-fill fs-3 text-danger me-3"></i>
                        <h5 class="mb-0 fw-semibold">Development & Ownership</h5>
                    </div>

                    <p class="text-muted mb-2">
                        This application is developed and maintained for internal gym
                        management purposes.
                    </p>

                    <p class="text-muted mb-0">
                        © <?= date('Y') ?> <strong>Gym App</strong><br>
                        Developed by <strong>Akhmad Noor</strong>
                        and <strong>Muhammad Haikal Habibie</strong>.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>