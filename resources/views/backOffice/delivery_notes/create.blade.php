@extends("backOffice.layout.panel")


@section("title","Créer une commande")

@section("style_links")
@endsection

@section("style")
    <style>
        .delivery-note-line-new {
            border-top: 2px solid #ddd;
            padding-top: 13px;
        }
    </style>
@endsection




@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Créer bon de livraison</h3>
                </div>
            </div>

            <div class="clearfix"></div>

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

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('deliveryNotes.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input class="form-control" type="hidden" value="{{ $order->id }}" data-validate-length-range="6" data-validate-words="2" name="order" />

                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" value="{{ $order->provider->name }}" data-validate-length-range="6" data-validate-words="2" name="provider"  disabled/>
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Détails du bon de livraison</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $order->title }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat de la commande</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="order_status" id="order_status" class="form-control" >
                                            <option value="" disabled>Selectionner L'état de la commande</option>
                                            <option value="In progress" >En cours</option>
                                            <option value="Delivered" >Delivrée</option>
                                            <option value="Skipped" >Annulée</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Document</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input id="document" type="file" name="documents" multiple>
                                    </div>
                                </div>

                                <div id="delivery-note-lines">
                                    @foreach($order->orderLines as $i => $ol)
                                        <input type="hidden" value="{{ $ol->id }}" class="form-control OrderID" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][id]" />
                                        <div class="delivery-note-line {{$i != 0 ? 'delivery-note-line-new' : ''}}">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ $ol->product->label . ' - ' . $ol->product->ref }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][product]" disabled  />
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $ol->Qte }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]"  />
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6 d-flex ">
                                                    <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event, {{$i}})"><i class="fa fa-trash-o"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('deliveryNotes.index')}}" class="btn btn-secondary">Annuler</a>
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
        function deleteLine(event, index) {
            event.preventDefault();

            var parentDiv = document.querySelectorAll('.delivery-note-line')[index];
            var OrderIDInput = document.querySelectorAll('.OrderID')[index];

            OrderIDInput.value = 'deleted';
            parentDiv.style.display = 'none';
        }
    </script>


@endsection
