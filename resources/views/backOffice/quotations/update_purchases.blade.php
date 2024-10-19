@extends("backOffice.layout.panel")


@section("title","Modifier Achats | Dossier {{$folder->id}}")

@section("style")

@endsection

@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Modifier Achats pour dossier : {{$folder->id}}</h3>
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
                            <form method="post" action="{{ route('folders.updatePurchases') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="folder" value="{{$folder->id}}">
                                <div id="lignesDevis">
                                    @foreach($folder->quotations as $i => $quotation)
                                        @if($quotation->type == "Accordé")
                                            @foreach($quotation->quotationLines as $i => $ql)
                                                @if($ql->type == "Produit")
                                                <div class="ligneDevis {{$i != 0 ? 'ligneDevis-new' : ''}}">
                                                <input type="hidden" value="{{ $ql->id }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][ql]" />

                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input disabled class="form-control" value="{{ $ql->description }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][description]"  />
                                                    </div>
                                                </div>

                                                <div class="provider-infos">
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <select name="lines[{{$i}}][exist_provider]" id="exist_provider" class="form-control exist_provider" >
                                                                <option value="" selected disabled>Selectionner Fournisseur</option>
                                                                @foreach($providers as $provider)
                                                                    <option {{$provider->id == $ql->provider_id ? "selected" : ""}} value="{{$provider->id}}">
                                                                        {{$provider->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <p style="padding: 5px;">
                                                                <input type="checkbox" name="lines[{{$i}}][new_provider]" id="nouveau-provider-check" value="Nouveau" class="flat" onChange="toggleNewProvider(event)"/> Nouveau Fournisseur
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="new-provider-form" style="display: none;">
                                                        <div id="formProvider">
                                                            <div class="field item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom </label>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <input class="form-control" value="{{ old('provider_name') }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][provider_name]"  />
                                                                </div>
                                                            </div>
                                                            <div class="field item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <input type="text" class="form-control" value="{{ old('provider_phone') }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][provider_phone]"  placeholder="ex. 0606060606"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Prix d'Achat </label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input class="form-control" value="{{ $ql->purchase_price }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][purchase_price]"  />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <hr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('folders.show', $folder->id)}}" class="btn btn-secondary">Annuler</a>
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

        function toggleNewProvider(event) {
            let NewProviderCheckbox = event.target;
            let ligneDevis = $(NewProviderCheckbox).closest('.ligneDevis');
            let providerForm = ligneDevis.find('.new-provider-form');
            let existProvider = ligneDevis.find('.exist_provider');

            if (NewProviderCheckbox.checked) {
                providerForm.show();
                existProvider.prop('disabled', true);
            } else {
                providerForm.hide();
                existProvider.prop('disabled', false);
            }
        }

    </script>

@endsection
