@extends("backOffice.layout.panel")


@section("title","Creer un Devis")

@section("style_links")
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section("style")
    <style>
        .ligneDevis-new {
            border-top: 2px solid #ddd;
            padding-top: 13px;
        }

        .select2-container {
            box-sizing: border-box;
            display: inline !important;
            margin: 0;
            position: relative;
            vertical-align: middle;
        }
    </style>
@endsection




@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Creer Devis</h3>
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
                            <form method="post" action="{{ route('quotations.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $folder->id }}" data-validate-length-range="6" data-validate-words="2" name="folder"/>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre de Devis</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('title') }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Type devis</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="type" class="form-control">
                                            <option {{empty($folder->quotations->get(0)) ? 'selected' : 'disabled'}} value="Initial" >Initial</option>
                                            <option {{isset($Accorde) ? 'selected' : 'disabled'}} value="Accordé">Accordé</option>
                                            <option {{!empty($folder->quotations->get(0)) && !isset($Accorde) ? 'selected' : 'disabled'}} value="Aditive" >Aditive</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <div id="lignesDevis">
                                    <div class="ligneDevis">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[0][type]" id="type" class="form-control select-type" onChange="handleSelectChange(event)">
                                                    <option value="" selected disabled >Selectionner le type</option>
                                                    <option value="Produit" >Produit</option>
                                                    <option value="MOD">MOD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="product-fields" style="display: none;">

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Produit</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="lines[0][exist_product]" class="form-control exist_product" >
                                                        <option value="" selected disabled>Selectionner Produit</option>
                                                        @foreach($products as $product)
                                                            <option value="{{$product->id}}">
                                                                {{$product->label . '-' . $product->ref}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <p style="padding: 5px;">
                                                        <input type="checkbox" name="lines[0][new_product]" id="new-product-check" value="Nouveau" class="flat" onChange="toggleNewProduct(event)"/> Nouveau Produit
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="new-product-form" style="display: none;">
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][label]"  placeholder="ex. PARE CHOC AV" />
                                                    </div>
                                                </div>

                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select name="lines[0][state]" id="state" class="form-control" onChange="handleSelectState(event)">
                                                            <option value="null" selected disabled>Selectionner l'état</option>
                                                            <option value="Occasion" >Occasion</option>
                                                            <option value="Nouveau">Neuf</option>
                                                            <option value="Adaptable">Adaptable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="field item form-group refInput" style="display: none;">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" value="{{ old('ref') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][ref]"  />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][quantity]"  />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mod-fields" style="display: none;">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('description') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][description]"  placeholder="ex. MONTAGE DEMONTAGE" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ old('price') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][price]"  placeholder="" />
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ old('TVA') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][TVA]"  placeholder="" />
                                            </div>
                                        </div>

                                        <div class="provider-infos"  style="display: none;">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <h4 class="text-center ">Infos Fournisseur</h4>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="lines[0][exist_provider]" class="form-control exist_provider" >
                                                        <option value="" selected disabled>Selectionner Fournisseur</option>
                                                        @foreach($providers as $provider)
                                                            <option value="{{$provider->id}}">
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
                                                        <input type="checkbox" name="lines[0][new_provider]" id="nouveau-provider-check" value="Nouveau" class="flat" onChange="toggleNewProvider(event)"/> Nouveau Fournisseur
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="new-provider-form" style="display: none;">
                                                <div id="formProvider">
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Nom </label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input class="form-control" value="{{ old('provider_name') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][provider_name]"  />
                                                        </div>
                                                    </div>
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input type="text" class="form-control" value="{{ old('provider_phone') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][provider_phone]"  placeholder="ex. 0606060606"  />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Prix d'Achat </label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('purchase_price') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][purchase_price]"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                        <button id="addDevisLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de Devis</button>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.exist_provider').select2({
                placeholder: "Selectionner Fournisseur",
                allowClear: true
            });
            $('.exist_product').select2({
                placeholder: "Selectionner Produit",
                allowClear: true
            });
        });
    </script>

    <script>
        function toggleNewProduct(event) {
            let NewProductCheckbox = event.target;
            let ligneDevis = $(NewProductCheckbox).closest('.ligneDevis');
            let productForm = ligneDevis.find('.new-product-form');
            let existProduct = ligneDevis.find('.exist_product');

            if (NewProductCheckbox.checked) {
                productForm.show();
                existProduct.prop('disabled', true);
            } else {
                productForm.hide();
                existProduct.prop('disabled', false);
            }
        }

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

        function handleSelectState(event) {
            let selectBox = event.target;
            let ligneDevis = $(selectBox).closest('.ligneDevis');
            let refInput = ligneDevis.find('.refInput');
            let selectedValue = selectBox.value;

            if (selectedValue === "Nouveau") {
                refInput.show();
            } else {
                refInput.hide();
            }
        }

        function handleSelectChange(event) {
            let selectBox = event.target;
            let ligneDevis = $(selectBox).closest('.ligneDevis');
            let productFields = ligneDevis.find('.product-fields');
            let providerFields = ligneDevis.find('.provider-infos');
            let modFields = ligneDevis.find('.mod-fields');
            let selectedValue = selectBox.value;

            if (selectedValue === "Produit") {
                productFields.show();
                providerFields.show();
                modFields.hide();
            } else {
                productFields.hide();
                providerFields.hide();
                modFields.show();
            }
        }
    </script>

    <script>
        function deleteLine(event) {
            // Supprimer la ligne de devis avec l'index donné
            event.preventDefault();

            var parentDiv = event.target.closest('.ligneDevis'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
            }
        }
    </script>

{{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addDevisLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = 0;
        function ajouterLigneDevis(e) {

            e.preventDefault();


            linesNumber++;

            const nouvelleLigne = document.createElement('div');
            nouvelleLigne.classList.add('ligneDevis');
            nouvelleLigne.classList.add('ligneDevis-new');
            nouvelleLigne.innerHTML = `
                </hr>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][type]" id="type" class="form-control select-type" onChange="handleSelectChange(event)">
                            <option value="" selected disabled>Selectionner le type</option>
                            <option value="Produit" >Produit</option>
                            <option value="MOD">MOD</option>
                        </select>
                    </div>
                </div>

                <div class="product-fields" style="display: none;">

                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Produit</label>
                        <div class="col-md-6 col-sm-6">
                            <select name="lines[${linesNumber}][exist_product]" class="form-control exist_product" >
                                <option value="" selected disabled>Selectionner Produit</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->label . '-' . $product->ref}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                        <div class="col-md-6 col-sm-6">
                            <p style="padding: 5px;">
                                <input type="checkbox" name="lines[${linesNumber}][new_product]" id="new-product-check" value="Nouveau" class="flat" onChange="toggleNewProduct(event)"/> Nouveau Produit
                            </p>
                        </div>
                    </div>

                    <div class="new-product-form" style="display: none;">
                        <div class="field item form-group">
                            <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][label]"  placeholder="ex. PARE CHOC AV" />
                            </div>
                        </div>

                        <div class="field item form-group">
                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="lines[${linesNumber}][state]" id="state" class="form-control" onChange="handleSelectState(event)">
                                    <option value="null" selected disabled>Selectionner l'état</option>
                                    <option value="Occasion" >Occasion</option>
                                    <option value="Nouveau">Neuf</option>
                                    <option value="Adaptable">Adaptable</option>
                                </select>
                            </div>
                        </div>

                        <div class="field item form-group refInput" style="display: none;">
                            <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" value="{{ old('ref') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][ref]"  />
                            </div>
                        </div>
                    </div>

                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][quantity]"  />
                        </div>
                    </div>
                </div>

                <div class="mod-fields" style="display: none;">
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" value="{{ old('description') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][description]"  placeholder="ex. MONTAGE DEMONTAGE" />
                        </div>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" value="{{ old('price') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][price]"  placeholder="" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" value="{{ old('TVA') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][TVA]"  placeholder="" />
                    </div>
                </div>
                <div class="provider-infos" style="display: none;">
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                        <div class="col-md-6 col-sm-6">
                            <select name="lines[${linesNumber}][exist_provider]" id="exist_provider" class="form-control">
                                <option value="" selected disabled>Selectionner Fournisseur</option>
                                @foreach($providers as $provider)
                                    <option value="{{$provider->id}}">
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
                                <input type="checkbox" name="lines[${linesNumber}][new_provider]" id="nouveau-provider-check" value="Nouveau" class="flat" onChange="toggleNewProvider(event)"/> Nouveau Fournisseur
                                </p>
                        </div>
                    </div>
                    <div class="new-provider-form" style="display: none;">
                        <div id="formPersonne">
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom </label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" value="{{ old('provider_name') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][provider_name]"  />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" value="{{ old('provider_phone') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][provider_phone]"  placeholder="ex. 0606060606"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Prix d'Achat </label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" value="{{ old('purchase_price') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][purchase_price]"  />
                        </div>
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6 d-flex ">
                        <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
`;


            document.getElementById('lignesDevis').appendChild(nouvelleLigne);

            $('.exist_provider').select2({
                placeholder: "Selectionner Fournisseur",
                allowClear: true
            });
            $('.exist_product').select2({
                placeholder: "Selectionner Produit",
                allowClear: true
            });
        }
    </script>


@endsection
