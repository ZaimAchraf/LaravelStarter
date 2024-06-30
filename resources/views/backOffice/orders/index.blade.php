@extends("backOffice.layout.panel")


@section("title","Liste des Commandes")

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
                    <h3>Gestion des Commandes</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                Liste des Commandes
                                <a class="ml-3" href="{{route('orders.create')}}">
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
                                            Pour Ajouter une commande merci de cliquer sur l'icone : <i class="fa fa-plus-circle"></i> a droite du titre : "Liste des commandes" au-dessus.
                                            <br>
                                        </p>

                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Fournisseur</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        {{$order->id}}
                                                    </td>
                                                    <td>
                                                        {{ $order->title }}
                                                    </td>
                                                    <td>
                                                        {{ $order->provider->name }}
                                                    </td>
                                                    <td>
                                                        {{ $order->created_at }}
                                                    </td>
                                                    <td>
                                                        @if($order->status == 'In progress')
                                                            <b style="color:  #f8bd3f">En cours</b>
                                                        @elseif($order->status == 'Delivered')
                                                            <b style="color: #1d8032">Deliverée</b>
                                                        @else
                                                            <b style="color: #9d0d1f" >Annulée</b>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <div class="d-flex p-0 m-0">

                                                            <a href="{{ route('orders.show', $order) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #007070;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            @if(!($order->status == 'Skipped'))

                                                            <a href="{{ route('deliveryNotes.create', $order->id) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #017fc0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                BL
                                                            </a>

                                                            @if(!(isset($order->deliveryNotes) && $order->deliveryNotes->isNotEmpty()))
                                                                <a href="{{ route('orders.edit', $order) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #2fff67;
                                                                        line-height: 1;
                                                                        padding: .4em .8em !important;
                                                                        display: flex;
                                                                        align-items: center"
                                                                >
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            @else
                                                                <form class="d-inline m-0" action="{{ route('orders.change_status') }}" method="POST">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="id" value="{{ $order->id }}">
                                                                    <input type="hidden" name="status" value="Delivered">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <button type="submit" class="text-white font-bold py-1 px-2 rounded mr-2"
                                                                            style="background: #156b19;
                                                                    border: none;
                                                                    margin: 0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;">

                                                                        <i class="fa fa-check"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <form class="d-inline m-0" action="{{ route('orders.change_status') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr que vous voulez annulé la demande?\nCette operation est irreductible!');">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                                <input type="hidden" name="status" value="Skipped">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <button type="submit" class="text-white font-bold py-1 px-2 rounded mr-2"
                                                                        style="background: #651526;
                                                                    border: none;
                                                                    margin: 0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;">

                                                                    <i class="fa fa-close"></i>
                                                                </button>
                                                            </form>

                                                            @endif

                                                            <form class="d-inline m-0" action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');">
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
