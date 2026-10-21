<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Admin') ?></title>

  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/dist/css/admin-menu.css') ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
  :root {
    --admin-topbar-height: 56px;
    --admin-sidebar-width: 260px;
  }

  html,
  body {
    height: 100%;
    margin: 0;
    overflow: hidden;
    background: #f8f9fa;
  }

  .admin-wrapper {
    display: flex;
    height: calc(100dvh - var(--admin-topbar-height));
    overflow: hidden;
  }

  .admin-sidebar {
    width: var(--admin-sidebar-width);
    min-width: var(--admin-sidebar-width);
    flex: 0 0 var(--admin-sidebar-width);
    flex-shrink: 0;
    height: calc(100dvh - var(--admin-topbar-height));
    overflow-y: auto;
    overflow-x: hidden;
    background: #212529;
  }

  .admin-sidebar .nav-link {
    color: #e9ecef;
  }

  .admin-sidebar .nav-link.active {
    background: #0d6efd;
    color: #fff;
  }

  .admin-main,
  .admin-wrapper > main,
  .admin-wrapper > .flex-grow-1 {
    flex: 1 1 auto;
    min-width: 0;
    min-height: 0;
    height: calc(100dvh - var(--admin-topbar-height));
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #fff;
  }

  .admin-content {
    flex: 1 1 auto;
    min-width: 0;
    min-height: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: auto;
    padding: 1rem;
    background: #fff;
  }

  .admin-content > .container,
  .admin-content > .container-fluid {
    flex: 1 1 auto;
    min-height: 100%;
  }

  .admin-wrapper > .flex-grow-1 {
    padding: 1rem;
    overflow-y: auto;
    overflow-x: auto;
    background: #fff;
  }

  .admin-backdrop {
    display: none;
  }

  @media (max-width: 1199.98px) {
    .admin-sidebar {
      position: fixed;
      top: var(--admin-topbar-height);
      left: 0;
      bottom: 0;
      height: auto;
      transform: translateX(-100%);
      transition: transform .25s ease;
      z-index: 1040;
    }

    .admin-sidebar.show {
      transform: translateX(0);
    }

    .admin-backdrop {
      display: none;
      position: fixed;
      top: var(--admin-topbar-height);
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, .35);
      z-index: 1030;
    }

    .admin-backdrop.show {
      display: block;
    }

    .admin-main,
    .admin-wrapper > main,
    .admin-wrapper > .flex-grow-1 {
      width: 100%;
    }
  }
</style>

</head>
<body>

<header class="navbar navbar-light bg-dark sticky-top px-3">
  <button class="btn btn-outline-secondary d-xl-none text-white" id="btnSidebar"><i class="bi bi-list"></i></button>
  <span class="navbar-brand text-white ms-2">Gym App</span>
  <div class="ms-auto d-flex align-items-center gap-3">
    <a href="#" class="text-secondary"><i class="bi bi-bell"></i></a>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle fs-5 me-2"></i> <span>Admin</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#">Profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</header>