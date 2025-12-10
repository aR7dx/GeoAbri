<div class="container-fluid"> 
    <div class="row"> 
        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary"> 
            <div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel"> 
                <div class="offcanvas-header"> 
                    <h5 class="offcanvas-title" id="sidebarMenuLabel">GeoAbri</h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button> 
                </div> 
                <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto"> 
                    
                    <ul class="nav flex-column">
                        <?php if (in_array('view_all_stats', $_SESSION['user']['permissions'])): ?>
                            <li class="nav-item py-2"> 
                                <a class="nav-link d-flex align-items-center gap-2 <?= str_ends_with($_SERVER['REDIRECT_URL'], '/dashboard') ? 'active' : '' ?>" href="<?= $router->generate('dashboard'); ?>"> 
                                    📊 Dashboard
                                </a> 
                            </li> 
                        <?php endif; ?>
                        
                        <li class="nav-item py-2"> 
                            <a class="nav-link d-flex align-items-center gap-2 <?= str_contains($_SERVER['REDIRECT_URL'], '/equipements') ? 'active' : '' ?>" href="<?= $router->generate('admin_equipements'); ?>"> 
                                🏛️ Gestion des équipements
                            </a> 
                        </li> 

                        <?php if (in_array('view_all_account', $_SESSION['user']['permissions'])): ?>
                            <li class="nav-item py-2"> 
                                <a class="nav-link d-flex align-items-center gap-2 <?= str_contains($_SERVER['REDIRECT_URL'], '/users') ? 'active' : '' ?>" href="<?= $router->generate('admin_users'); ?>"> 
                                    👥 Gestion des utilisateurs
                                </a> 
                            </li>
                        <?php endif; ?>
                        
                        <?php if (in_array('create_alert', $_SESSION['user']['permissions']) || in_array('view_all_alerts', $_SESSION['user']['permissions'])): ?>
                            <li class="nav-item py-2"> 
                                <a class="nav-link d-flex align-items-center gap-2 <?= str_contains($_SERVER['REDIRECT_URL'], '/alert') ? 'active' : '' ?>" href="<?= $router->generate('admin_alerts'); ?>"> 
                                    🚨 Gestion des alertes
                                </a> 
                            </li>
                        <?php endif; ?>

                        <?php if (in_array('accept_deny_request', $_SESSION['user']['permissions'])): ?>
                            <li class="nav-item py-2"> 
                                <a class="nav-link d-flex align-items-center gap-2 <?= str_contains($_SERVER['REDIRECT_URL'], '/pendings') ? 'active' : '' ?>" href="<?= $router->generate('admin_pendings'); ?>"> 
                                    ⏳ Demandes en attentes
                                </a> 
                            </li> 
                        <?php endif; ?>
                    </ul> 
                    
                    <hr class="my-3">

                    <ul class="nav flex-column mb-auto"> 
                        <li class="nav-item py-2"> 
                            <a class="nav-link d-flex align-items-center gap-2" href="<?= $router->generate('home'); ?>"> 
                                🏡 Retour au site
                            </a> 
                        </li>
                    </ul>
                </div> 
            </div> 
        </div> 