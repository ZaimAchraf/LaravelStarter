@extends("backOffice.layout.panel")


@section("title","Details Dossier Client")

@section("style_links")

    <link href="{{asset("adminPanel")}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("adminPanel")}}/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

@endsection

@section("style")

    <style>
        .dataTables_length, .dataTables_filter, div#datatable-responsive_paginate, .dataTables_info {
            display: none !important;
        }

        .doc {
            display: none;
        }

        .doc.active {
            display: flex; /* Ou block selon votre design */
        }

        .text-center {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 10px;
        }

        .doc_pag {
            /*padding: 10px 15px;*/
            flex: 1 1 auto; /* Chaque bouton prend l'espace disponible */
            max-width: 150px; /* Limiter la largeur maximale sur les grands écrans */
            text-align: center;
            cursor: pointer;
            /*font-size: .8 em;*/
        }
    </style>

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
                    <h3>Dossier : {{$folder->title}} </h3>
                </div>
            </div>

            <div class="clearfix"></div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert" id="myAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible" role="alert" id="myAlert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            @if($folder->type == 'sinistre')

            @if (!$steps["Images avant"])
                <div class="alert alert-info alert-dismissible" role="alert" id="myAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    Les images Avant Sont toujours absentes !
                </div>
            @elseif(!$steps["Images en cours"])
                <div class="alert alert-info alert-dismissible" role="alert" id="myAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    Les images en cours Sont toujours absentes !
                </div>
            @elseif(!$steps["Images après"])
                <div class="alert alert-info alert-dismissible" role="alert" id="myAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    Les images Apres Sont toujours absentes !
                </div>
            @endif

            <!-- Progress bar pour montrer la progression globale -->
            <div class="container mt-4">
                @foreach($steps as $stepName => $isComplete)
                    <span class="badge {{ $isComplete ? 'bg-success' : 'bg-secondary' }} mb-1" style="color: white !important;">
                        {{ $stepName }} {{ $isComplete ? '✔️' : '❌' }}
                    </span>
                @endforeach
                <div class="progress">
                    @php
                        $steps = collect($steps);

                        // Filtrer les étapes complètes et incomplètes
                        $completedSteps = $steps->filter(function ($completed) {
                            return $completed; // True pour les étapes complètes
                        })->keys();

                        $incompleteSteps = $steps->filter(function ($completed) {
                            return !$completed; // False pour les étapes incomplètes
                        })->keys();

                        $totalSteps = $steps->count();
                        $completedCount = $completedSteps->count();
                        $completedPercentage = ($completedCount / $totalSteps) * 100;
                    @endphp

                        <!-- Barre de progression avec étapes complètes et incomplètes -->
                    <div class="progress-bar bg-success pt-1 pb-1 " role="progressbar" style="width: {{ $completedPercentage }}%;">
                        <div class="pt-1">
                            {{ $completedCount }} étapes OK
                        </div>
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ 100 - $completedPercentage }}%;">
                        {{ $totalSteps - $completedCount }} étapes KO
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                Details du client et vehicule
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
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <strong>Client :</strong>
                                    <br>
                                    Nom : {{$folder->client->name}}<br>
                                    Tel : {{$folder->client->phone}}
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <strong>Vehicule :</strong>
                                    <br>
                                    Marque : {{$folder->vehicle->label}}<br>
                                    Immatricule : {{$folder->vehicle->registration}}<br>
                                    {!! $folder->vehicle->insurance ? 'Assurance : ' . $folder->vehicle->insurance . '<br>' : '' !!}
                                    {!! $folder->vehicle->chassis_number ? 'Num chassier : ' . $folder->vehicle->chassis_number . '<br>' : '' !!}
                                    {!! $folder->vehicle->police_number ? 'Num police : ' . $folder->vehicle->police_number . '<br>' : '' !!}
                                    {!! $folder->vehicle->mileage ? 'Kilometrage : ' . $folder->vehicle->mileage . '<br>' : '' !!}
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    @if(isset($folder->credit) && $folder->credit->total != 0)
                                        <strong>Credit : @if($folder->credit->total == $folder->credit->paid) <span class="text-success">(OK)</span> @endif </strong>
                                        <br>
                                        Total : <a target="_blank" href="{{ route('credits.payments', $folder->credit) }}"><b><span class="text-primary">{{$folder->credit->total}} DH</span></b></a><br>
                                        Payé  : <b><span class="text-danger">{{$folder->credit->paid}} DH</span></b><br>
                                    @else
                                        <strong>
                                            Aucun Credit pour ce dossier
                                        </strong>
                                    @endif
                                </div>
                                <!-- /.col -->
                            </div>
                            {{--                            <hr>--}}
                            <div class="x_title">
                                <h2>
                                    Details des devis
                                    <a class="ml-3" href="{{route('folders.quotations.create', $folder->id)}}">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Type</th>
                                                <th>Nom Client</th>
                                                <th>Vehicule</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $add = 0;
                                            ?>
                                            @foreach ($folder->quotations as $quotation)
                                                <?php
                                                    if ($quotation->type == 'Aditive') $add++;
                                                ?>
                                                <tr>
                                                    <td>
                                                        {{$quotation->id}}
                                                    </td>
                                                    <td>
                                                        <b>{{$quotation->type}}</b>
                                                    </td>
                                                    <td>
                                                        {{ $quotation->folder->client->name }}
                                                    </td>
                                                    <td>
                                                        {{ $quotation->folder->vehicle->label }}
                                                    </td>
                                                    <td>
                                                        {{ $quotation->created_at }}
                                                    </td>
                                                    <td>
                                                        <b>{{ $quotation->total }} DH</b>
                                                    </td>
                                                    <td>
                                                        @if($quotation->is_active)
                                                            <b style="color: #208522">Accordé</b>
                                                        @else
                                                            <b style="color: #f8bd3f">Non Accordé</b>
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
                                                            <a href="{{ route('quotations.getPDF', [$quotation, $add]) }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="background: #039dab;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
                                                            @if(!$quotation->is_active)
                                                                <a href="{{ route('quotations.activate', $quotation) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #74fdd2;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                                >
                                                                    <i class="fa fa-check"></i>
                                                                </a>
                                                            @else

                                                                <a href="{{ route('quotations.getBL', $quotation) }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="background: #006244;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                                >
                                                                    BL
                                                                </a>
                                                            @endif

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

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>

                            <div class="x_title mt-4">
                                <h2>
                                    Details des factures
                                    <a class="ml-3" href="{{route('invoices.create', $folder->id)}}">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">

                                        <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Titre</th>
                                                <th>Nom Client</th>
                                                <th>Vehicule</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($folder->invoices as $invoice)
                                                <tr>
                                                    <td>
                                                        {{$invoice->id}}
                                                    </td>
                                                    <td>
                                                        {{$invoice->title}}
                                                    </td>
                                                    <td>
                                                        {{ $folder->client->name }}
                                                    </td>
                                                    <td>
                                                        {{ $folder->vehicle->label }}
                                                    </td>
                                                    <td>
                                                        {{ $invoice->created_at }}
                                                    </td>
                                                    <td>
                                                        <b>{{ $invoice->total }} DH</b>
                                                    </td>
                                                    <td>

                                                        <div class="d-flex p-0 m-0">
                                                            <a href="{{ route('invoices.edit', ['invoice' => $invoice]) }}" class="text-white font-bold px-2 rounded mr-2" style="background: #2fff67;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('invoices.getPDF', [$invoice, 0]) }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="background: #039dab;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>


                                                            <a href="{{ route('invoices.getPDF', [$invoice, 1]) }}" target="_blank" class="text-white font-bold px-2 rounded mr-2" style="background: white;
                                                                    line-height: 1;
                                                                    padding: .4em .8em !important;
                                                                    display: flex;
                                                                    color: #e03939 !important;
                                                                    align-items: center"
                                                            >
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>

                                                            <form class="d-inline m-0" action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');">
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

                            <div class="x_title mt-4">
                                <h2>
                                    Achats
                                    <a class="ml-3" target="_blank" href="{{route('folders.editPurhases', $folder->id)}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <!-- /.col -->
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">

                                        <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>Fournisseur</th>
                                                <th>Produit</th>
                                                <th>Prix</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($folder->quotations as $quotation)
                                                    @if($quotation->type == "Accordé")
                                                        @foreach($quotation->quotationLines as $ql)
                                                            @if($ql->provider)
                                                                <tr>
                                                                    <td style="width:40%">{{$ql->provider->name}}</td>
                                                                    <td style="width:40%">{{$ql->description}}</td>
                                                                    <td>{{$ql->purchase_price}} DH</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>

                            <div class="x_title mt-4 doc_vehicle">
                                <h2>
                                    Documents & Photos
                                </h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="docs_pagination text-center">
                                <span class="doc_pag active btn btn-primary mr-2">docs de véhicule</span>
                                <span class="doc_pag btn btn-primary mr-2">docs d'assurance</span>
                                <span class="doc_pag btn btn-primary mr-2">photos avant</span>
                                <span class="doc_pag btn btn-primary mr-2">photos en cours</span>
                                <span class="doc_pag btn btn-primary mr-2">photos apres</span>
                                <span class="doc_pag btn btn-primary mr-2">docs Facturation</span>
                            </div>
                            <div id="doc_vehicle" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'DV')
                                    <div class="col-md-55">
                                        <div class="thumbnail">
                                                <div class="image view view-first">

                                                    <img style="width: 100%; display: block;" src="{{asset('images/pdf-la-gi.jpg')}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <p class="text-center"><a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank">{{$doc->label}}</a></p>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div id="doc_assurance" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'DA')
                                    <div class="col-md-55">
                                        <div class="thumbnail">
                                                <div class="image view view-first">

                                                    <img style="width: 100%; display: block;" src="{{asset('images/pdf-la-gi.jpg')}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <p class="text-center"><a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank">{{$doc->label}}</a></p>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach

                            </div>

                            <div id="img_av" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'IMG_AV')
                                    <div class="col-md-55">
                                        <div class="thumbnail">
                                                <div class="image view view-first">

                                                    <img style="width: 100%; display: block;" src="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach

                            </div>

                            <div id="img_ec" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'IMG_EC')
                                    <div class="col-md-55">
                                        <div class="thumbnail">
                                                <div class="image view view-first">

                                                    <img style="width: 100%; display: block;" src="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach

                            </div>

                            <div id="img_ap" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'IMG_AP')
                                    <div class="col-md-55">
                                        <div class="thumbnail">
                                                <div class="image view view-first">

                                                    <img style="width: 100%; display: block;" src="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach

                            </div>

                            <div id="doc_facturation" class="row doc">
                                @foreach($folder->documents as $doc)
                                    @if($doc->type == 'DF')
                                        <div class="col-md-55">
                                            <div class="thumbnail">
                                                <div class="image view view-first">
                                                    <img style="width: 100%; display: block;" src="{{asset('images/pdf-la-gi.jpg')}}" alt="image" />
                                                    <div class="mask">
                                                        <div class="tools tools-bottom">
                                                            <a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <p class="text-center"><a href="{{asset('uploads/dossiers_clients/'. $folder->client->name .'/' . $doc->name)}}" target="_blank">{{$doc->label}}</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <form method="post" action="{{ route('folders.uploadFiles', $folder->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="type" class="form-control">
                                                <option disabled selected >Selectionner le type document</option>
                                                <option value="DV" >Document Vehicule</option>
                                                <option value="DA" >Document Assurance</option>
                                                <option value="IMG_AV" >Photos Avant</option>
                                                <option value="IMG_EC" >Photos En Cours</option>
                                                <option value="IMG_AP" >Photos Apres</option>
                                                <option value="DF" >Document Facturation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">libellé</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" value="" data-validate-length-range="6" data-validate-words="2" name="label"  />
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Document</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input id="picture" type="file" name="documents[]" multiple>
                                        </div>
                                    </div>
                                    <div class="ln_solid mt-1">
                                        <div class="form-group">
                                            <div class="col-md-6 offset-md-3  pt-2">
                                                <button type='submit' class="btn btn-primary">Ajouter</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section("script")
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Récupérer tous les boutons de navigation
            let docButtons = document.querySelectorAll('.doc_pag');

            // Ajouter un écouteur d'événement pour chaque bouton
            docButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Supprimer la classe active de tous les boutons
                    docButtons.forEach(btn => btn.classList.remove('active'));

                    // Ajouter la classe active au bouton cliqué
                    this.classList.add('active');

                    // Récupérer l'ID de la section à afficher
                    let sectionToShow;
                    switch(this.textContent.trim()) {
                        case 'docs de véhicule':
                            sectionToShow = 'doc_vehicle';
                            break;
                        case 'docs d\'assurance':
                            sectionToShow = 'doc_assurance';
                            break;
                        case 'photos avant':
                            sectionToShow = 'img_av';
                            break;
                        case 'photos en cours':
                            sectionToShow = 'img_ec';
                            break;
                        case 'photos apres':
                            sectionToShow = 'img_ap';
                            break;
                        case 'docs Facturation':
                            sectionToShow = 'doc_facturation';
                            break;
                    }

                    // Cacher toutes les sections de documents
                    let docSections = document.querySelectorAll('.doc');
                    docSections.forEach(section => section.style.display = 'none');

                    // Afficher uniquement la section correspondante
                    document.getElementById(sectionToShow).style.display = 'flex';
                });
            });

            // Optionnel: Afficher uniquement la première section au chargement de la page
            document.getElementById('doc_vehicle').style.display = 'flex'; // Par défaut, on affiche les docs de véhicule
        });

    </script>
@endsection
