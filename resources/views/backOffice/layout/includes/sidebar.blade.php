
{{--Sidebar start--}}
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;margin-top: 10px;">
            <a href="{{route('dashboard')}}" class="site_title"><i class="fa fa-car"></i> <span>Auto Shop !</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{asset('uploads')}}/users/{{Auth::user()->picture}}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{Auth::user()->username}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('dashboard')}}">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-users"></i> Gestion Utilisateurs <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('users.index')}}">Liste des Utilisateurs</a></li>
                            <li><a href="{{route('employees.index')}}">Liste des Fonctionnaires</a></li>
                            <li><a href="{{route('providers.index')}}">Liste des Fournisseurs</a></li>
                            <li><a href="{{route('clients.index')}}">Liste des Client</a></li>
                        </ul>
                    </li>
                </ul>

{{--                <ul class="nav side-menu">--}}
{{--                    <li><a><i class="fa fa-files-o"></i> DOCS Clients <span class="fa fa-chevron-down"></span></a>--}}
{{--                        <ul class="nav child_menu">--}}
{{--                            <li><a href="{{route('quotations.index')}}">Liste des Devis</a></li>--}}
{{--                            <li><a href="{{route('credits.index')}}">Liste des Crédits</a></li>--}}
{{--                            <li><a href="{{route('invoices.index')}}">Liste des Factures</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                </ul>--}}

                <ul class="nav side-menu">
                    <li><a><i class="fa fa-files-o"></i> Dossiers Clients <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('folders.index')}}">Tous les dossiers</a></li>
                            <li><a href="{{route('folders.foldersType', 'sinistre')}}">Dossiers Sinistre</a></li>
                            <li><a href="{{route('folders.foldersType', 'service')}}">Dossiers Service</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('aggregatedInvoices.index')}}">
                            <i class="fa fa-file-archive-o"></i> Factures Aggrégée
                        </a>
                    </li>
                </ul>

                <ul class="nav side-menu">
                    <li><a><i class="fa fa-files-o"></i> DOCS Fournisseur<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('orders.index')}}">Liste des Commandes</a></li>
                            <li><a href="{{route('deliveryNotes.index')}}">Liste des BL</a></li>
                            <li><a href="{{route('supplierCredits.index')}}">Liste des Crédits</a></li>
{{--                            <li><a href="#">Liste des Factures</a></li>--}}
                        </ul>
                    </li>
                </ul>

                <ul class="nav side-menu">
                    <li><a><i class="fa fa-files-o"></i> Comptabilité <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('accountants.invoices')}}">Factures Achats</a></li>
                            <li><a href="{{route('accountants.products')}}">Stock</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav side-menu">
                    <li>
                        <a href="{{route('products.index')}}">
                            <i class="fa fa-product-hunt"></i> Liste des produits
                        </a>
                    </li>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
{{--                <a style="margin-top: -10px;" class="dropdown-item preview-item" href="{{ route('logout') }}"--}}
{{--                   onclick="event.preventDefault();this.closest('form').submit();">--}}
{{--                    <div class="preview-item-content text-center text-purple-600">--}}
{{--                        Se déconnecter--}}
{{--                    </div>--}}
{{--                </a>--}}
                <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html"
                   onclick="event.preventDefault();this.closest('form').submit();">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            </form>

        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
{{--Sidebar End--}}
