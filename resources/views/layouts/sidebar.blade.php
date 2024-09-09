<div id="app-sidepanel" class="app-sidepanel">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
            <a class="app-logo" href="/"><img class="logo-icon me-2" src="{{ asset('images/logo-ONEA.jpg') }}" alt="logo"><span class="logo-text">CongeTrack</span></a>
        </div><!--//app-branding-->

        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                <li class="nav-item">
                    <a class="nav-link active" href="/" >  <!--href="/" pour changer couleur sidebar-->
                        <span class="nav-icon">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="nav-link-text">Menu Principale</span>
                    </a><!--//nav-link-->
                </li><!--//nav-item-->

                <li class="nav-item has-submenu">
                @if(auth()->user()->profil == 'manager' || auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                    <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-1" aria-expanded="false" aria-controls="submenu-1">
                        <span class="nav-icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="nav-link-text">Configuration</span>
                        <span class="submenu-arrow">
                            <i class="fas fa-chevron-down"></i>
                        </span><!--//submenu-arrow-->
                    </a><!--//nav-link-->
                    <div id="submenu-1" class="collapse submenu submenu-1" data-bs-parent="#menu-accordion">
                        <ul class="submenu-list list-unstyled">
                            <li class="submenu-item"><a class="submenu-link" href="{{ route('departements.index') }}">Departement</a></li>
                            <li class="submenu-item"><a class="submenu-link" href="{{ route('postes.index') }}">Poste</a></li>

                            @if(auth()->user()->profil == 'responsables RH')
                                <li class="submenu-item"><a class="submenu-link" href="{{ route('typeConges.index') }}">Type de congés</a></li>
                                <li class="submenu-item"><a class="submenu-link" href="{{ route('typeAbsences.index') }}">Type d'absences</a></li>
                                <li class="submenu-item"><a class="submenu-link" href="{{ route('user-manager.index') }}">Workflow</a></li>
                            @endif
                        </ul>
                    </div>
                @endif
                </li><!--//nav-item-->

                <ul class="app-menu">
                    @if(auth()->user()->profil == 'employés' || auth()->user()->profil == 'manager' || auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                        <li class="nav-item has-submenu">
                            <a class="nav-link" href="{{ route('conges.index') }}">
                                <span class="nav-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                                <span class="nav-link-text">Gestion des Congés</span>
                            </a>
                        </li>

                        @if(auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('conges.liste_conges') }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-list-alt"></i>
                                    </span>
                                    <span class="nav-link-text">Liste autorisés pour les congés</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absences.index') }}">
                                <span class="nav-icon">
                                    <i class="fas fa-user-times"></i>
                                </span>
                                <span class="nav-link-text">Gestion des Absences</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item has-submenu">
                        @if(auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-reports" aria-expanded="false" aria-controls="submenu-reports">
                                <span class="nav-icon">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <span class="nav-link-text">Rapports</span>
                                <span class="submenu-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </span><!--//submenu-arrow-->
                            </a><!--//nav-link-->
                            <div id="submenu-reports" class="collapse submenu submenu-reports" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="{{ route('rapports.enCours') }}">Congés en cours</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="{{route('rapports.moisProchain')}}">Prevision Congés</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="{{route('rapportsAbsences.enCours')}}">Absences en cours</a></li>
                
                                </ul>
                            </div>
                        @endif
                    </li><!--//nav-item-->
        
                    @if(auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absences.attente') }}">
                                <span class="nav-icon">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <span class="nav-link-text">Absences plus 3 jours</span>
                            </a>
                        </li>
                    @endif
<!--
                    @if(auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admins.import.form') }}">
                                    <span class="nav-icon">
                                        <i class="fas fa-list-alt"></i>
                                    </span>
                                    <span class="nav-link-text">Import </span>
                                </a>
                            </li>
                    @endif
-->
                </ul>

                <div class="app-sidepanel-footer">
                    <nav class="app-nav app-nav-footer">
                        <ul class="app-menu footer-menu list-unstyled">
                            <li class="nav-item">
                                @if(auth()->user()->profil == 'administrateurs' || auth()->user()->profil == 'responsables RH')
                                    <a class="nav-link" href="{{route('admins.index')}}">
                                        <span class="nav-icon">
                                            <i class="fas fa-user-cog"></i>
                                        </span>
                                        <span class="nav-link-text">Gestion des Utilisateurs</span>
                                    </a><!--//nav-link-->
                                @endif
                            </li><!--//nav-item-->
                        </ul><!--//footer-menu-->

                        <br>
                        <br>

                        @php
                            $days = (new \DateTime(now()))->diff(new \DateTime(auth()->user()->initialization_date))->days + 1;
                            $nbreConge = ($days * 2.5) / 30;
                            $congeRestant = floor(($nbreConge + auth()->user()->initial) - auth()->user()->pris);
                        @endphp

                        <center>
                            <span class="nav-link-text">
                                <h5>Mes congés restants</h5>
                                <br>
                                <h2> {{$congeRestant}} jours</h2>
                            </span>
                        </center>
                    </nav><!--//app-nav-footer-->
                </div><!--//app-sidepanel-footer-->
            </ul>
        </nav><!--//app-nav-->
    </div><!--//sidepanel-inner-->
</div><!--//app-sidepanel-->
