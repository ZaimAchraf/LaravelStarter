@extends("backOffice.layout.panel")


@section("title","Liste des utilisateurs")

@section("style_links")

    <link href="{{asset("adminPanel")}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

@endsection

@section("script_links")

    <script src="{{asset("adminPanel")}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset("adminPanel")}}/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="{{asset("adminPanel")}}/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset("adminPanel")}}/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="{{asset("adminPanel")}}/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="{{asset("adminPanel")}}/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>

@endsection




@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Gestion des Utilisateurs</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                Liste des utilisateurs
                                <a class="ml-3" href="{{route('users.create')}}">
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            Pour Ajouter un utilisateur merci de cliquer sur l'icone : <i class="fa fa-plus-circle"></i> a droite du titre : "Liste des utilisateurs" au-dessus.
                                            <br>
                                            Pour desactiver/activer un utilisateur vous cliquez sur l'icone <i class="fa fa-close"></i>/<i class="fa fa-check"></i>
                                            dans ligne correspendante.
                                            Un utilisateur avec le statut "Desactive" ne peut pas se onnecter.
                                        </p>

                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Nom Complet</th>
                                                <th>Email</th>
                                                <th>Nom d'utilisateur</th>
                                                <th>Telephone</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                        <img src="{{asset('uploads')}}/users/{{ $user->picture }}" width="70px">
                                                    </td>
                                                    <td>
                                                        {{ $user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                    <td>
                                                        {{ $user->username }}
                                                    </td>
                                                    <td>
                                                        {{ $user->phone }}
                                                    </td>
                                                    <td>
                                                        <span style="word-wrap: break-word;">
                                                            @if($user->is_active)
                                                                <b class="text-success">Active</b>
                                                            @else
                                                                <b class="text-danger">Desactive</b>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>

                                                        <div class="d-flex p-0 m-0">
                                                            <a href="{{ route('users.edit', $user) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #2fff67;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            <form class="d-inline m-0" action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <button type="submit" class="text-white font-bold py-1 px-2 rounded mr-2"
                                                                        style="background: #ff2f47;
                                                                    border: none;
                                                                    margin: 0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;">

                                                                    <i class="fa fa-trash-o"></i>
                                                                </button>
                                                            </form>
                                                            <form class="d-inline m-0" action="{{ route('users.enable_disable', $user->id) }}" method="POST">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <button type="submit" class="text-white font-bold py-1 px-2 rounded mr-2"
                                                                        style="background: {{$user->is_active ? '#ff2f47' : '#2fff67'}} ;
                                                                    border: none;
                                                                    margin: 0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;">

                                                                    @if($user->is_active)
                                                                        <i class="fa fa-close"></i>
                                                                    @else
                                                                        <i class="fa fa-check"></i>
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
