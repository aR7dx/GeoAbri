
<div class="container-fluid h-100"> 
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
                        <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= $router->generate('dashboard'); ?>"> 
                            📊 Dashboard
                        </a> 
                    </li> 
                <?php endif; ?>
                
                <li class="nav-item py-2"> 
                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= $router->generate('dashboard_equipements_management'); ?>"> 
                        🏛️ Gestion des équipements
                    </a> 
                </li> 

                <?php if (in_array('view_all_account', $_SESSION['user']['permissions'])): ?>
                    <li class="nav-item py-2"> 
                        <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= $router->generate('dashboard_users_management'); ?>"> 
                            👥 Gestion des utilisateurs
                        </a> 
                    </li>
                <?php endif; ?>
                
                <li class="nav-item py-2"> 
                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="#"> 
                        🚨 Gestion des alertes
                    </a> 
                </li> 
                <li class="nav-item py-2"> 
                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= $router->generate('dashboard_pending_management'); ?>"> 
                        ⏳ Demandes en attentes
                    </a> 
                </li> 
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