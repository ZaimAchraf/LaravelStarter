@extends("backOffice.layout.panel")


@section("title","Liste des Devis")

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
                    <h3>Gestion des Devis</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                Liste des Devis
                                <a class="ml-3" href="{{route('quotations.create')}}">
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
                                            Pour Ajouter un devis merci de cliquer sur l'icone : <i class="fa fa-plus-circle"></i> a droite du titre : "Liste des Devis" au-dessus.
                                            <br>
                                        </p>

                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom Client</th>
                                                <th>Vehicule</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Credit</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($quotations as $quotation)
                                                <tr>
                                                    <td>
                                                        {{$quotation->id}}
                                                    </td>
                                                    <td>
                                                        {{ $quotation->client->name }}
                                                    </td>
                                                    <td>
                                                        {{ $quotation->vehicle->label }}
                                                    </td>
                                                    <td>
                                                        {{ $quotation->created_at }}
                                                    </td>
                                                    <td>
                                                        <b>{{ $quotation->total }} DH</b>
                                                    </td>
                                                    <td>
                                                        @if($quotation->is_active)
                                                            @if($quotation->credit->total == $quotation->credit->paid)
                                                                <a href="{{ route('credits.index') }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="
                                                                color: #00ff29 !important;
                                                                font-weight: bold;"
                                                                >
                                                                    OK
                                                                </a>
                                                            @else
                                                                <a href="{{ route('credits.index') }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="
                                                                color: #ff2c2c !important;
                                                                font-weight: bold;"
                                                                >
                                                                    {{$quotation->credit->total - $quotation->credit->paid}} DH
                                                                </a>
                                                            @endif

                                                        @else
                                                            <b style="color: #f8bd3f">Non Confirmé</b>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <div class="d-flex p-0 m-0">
                                                            <a href="{{ route('quotations.edit', $quotation) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #2fff67;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            <form class="d-inline m-0" action="{{ route('quotations.destroy', $quotation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');">
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
                                                            <a href="{{ route('quotations.getPDF', $quotation) }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="background: #039dab;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                            @if(!$quotation->is_active)
                                                                <form class="d-inline m-0" action="{{ route('quotations.activate', $quotation->id) }}" method="POST">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <button type="submit" class="text-white font-bold py-1 px-2 rounded mr-2"
                                                                            style="background: #74fdd2;
                                                                    border: none;
                                                                    margin: 0;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;">

                                                                        <i class="fa fa-check"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

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
