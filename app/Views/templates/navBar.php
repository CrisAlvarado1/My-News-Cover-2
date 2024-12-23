<nav class="navbar navbar-expand-lg p-md-3 background-color sticky-top">
    <div class="container">
        <img src="/images/general/logo.png" alt="Logo" class="logo">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="text-muted">
                <i class="fa-solid fa-bars"></i>
            </span>
        </button>
        <?php if (!isset($userCover)) { ?>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="mx-auto"></div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="dropdown mt-2">
                            <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                </svg>
                                <?php echo (isset($session)) ? $session->get('name') : 'Login'; ?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <?php if (isset($session)) { ?>
                                    <a class="dropdown-item" href="<?php echo site_url('logout') ?>">Logout</a>
                                    <!-- Dropdown options for the administrator user: -->
                                    <?php if ($session->get('role')  === 'admin') : ?>
                                        <a class="dropdown-item" href="<?php echo site_url('admin/index') ?>">Categories</a>
                                    <?php endif; ?>
                                    <!-- Dropdown options for the normal user: -->
                                    <?php if ($session->get('role') === 'user') : ?>
                                        <a class="dropdown-item" href="<?php echo site_url('users/newsSources/index') ?>">News Sources</a>
                                        <a class="dropdown-item" href="<?php echo site_url('users/news/index') ?>">My Cover</a>
                                    <?php endif; ?>
                                <?php } else { ?>
                                    <!-- Dropdown options for the login and signup area: -->
                                    <a class="dropdown-item" href="<?php echo site_url('/') ?>">Login</a>
                                    <a class="dropdown-item" href="<?php echo site_url('users/index')  ?>">Sign Up</a>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        <?php } else { ?>
            <!-- Show the name of the owner of the cover on the public cover -->
            <span class="font-weight-bold"><?php echo 'Cover of ' . $userCover ?></span>
        <?php } ?>
    </div>
</nav>