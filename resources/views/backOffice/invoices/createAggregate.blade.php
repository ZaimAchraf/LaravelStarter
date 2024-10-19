@extends("backOffice.layout.panel")


@section("title","Creer une facture aggrégée")

@section("style_links")
@endsection

@section("style")
    <style>
        .invoiceLine-new {
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
                    <h3>Créer Facture aggrégée</h3>
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
                            <form method="post" action="{{ route('invoices.createAggregate') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" value="" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy"  />
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Facture</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div id="invoiceLines">
                                    <div class="invoiceLine">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[0][type]" id="type" class="form-control select-type" onChange="handleSelectChange(event)">
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
                                                    <select name="lines[0][exist_product]" class="form-control exist_product" >
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
                                                        <input type="text" value="{{ old('ref') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][reference]"  />
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
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6 d-flex ">
                                                <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                        <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                            <button id="addInvoiceLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de Facture</button>
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="field item form-group">--}}
{{--                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>--}}
{{--                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">--}}
{{--                                        <button id="addInvoiceLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de Facture</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

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
        function deleteLine(event) {
            // Supprimer la ligne de devis avec l'index donné
            event.preventDefault();

            var parentDiv = event.target.closest('.invoiceLine'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
            }
        }
    </script>

    <script>
        function toggleNewProduct(event) {
            let NewProductCheckbox = event.target;
            let invoiceLine = $(NewProductCheckbox).closest('.invoiceLine');
            let productForm = invoiceLine.find('.new-product-form');
            let existProduct = invoiceLine.find('.exist_product');

            if (NewProductCheckbox.checked) {
                productForm.show();
                existProduct.prop('disabled', true);
            } else {
                productForm.hide();
                existProduct.prop('disabled', false);
            }
        }

        function handleSelectState(event) {
            let selectBox = event.target;
            let invoiceLine = $(selectBox).closest('.invoiceLine');
            let refInput = invoiceLine.find('.refInput');
            let selectedValue = selectBox.value;

            if (selectedValue === "Nouveau") {
                refInput.show();
            } else {
                refInput.hide();
            }
        }

        function toggleNewProvider(event) {
            let NewProviderCheckbox = event.target;
            let invoiceLine = $(NewProviderCheckbox).closest('.invoiceLine');
            let providerForm = invoiceLine.find('.new-provider-form');
            let existProvider = invoiceLine.find('.exist_provider');

            if (NewProviderCheckbox.checked) {
                providerForm.show();
                existProvider.prop('disabled', true);
            } else {
                providerForm.hide();
                existProvider.prop('disabled', false);
            }
        }

        function handleSelectChange(event) {
            let selectBox = event.target;
            let invoiceLine = $(selectBox).closest('.invoiceLine');
            let productFields = invoiceLine.find('.product-fields');
            let providerFields = invoiceLine.find('.provider-infos');
            let modFields = invoiceLine.find('.mod-fields');
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

    {{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addInvoiceLine').addEventListener('click', addInvoiceLigne);
        let linesNumber = document.querySelectorAll(".invoiceLine").length;
        function addInvoiceLigne(e) {

            e.preventDefault();

            const nouvelleLigne = document.createElement('div');
            nouvelleLigne.classList.add('invoiceLine');
            nouvelleLigne.classList.add('invoiceLine-new');
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
                            <input type="text" value="{{ old('ref') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][reference]"  />
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
            <div class="field item form-group">
                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                <div class="col-md-6 col-sm-6 d-flex ">
                    <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
`;

            linesNumber++;

            document.getElementById('invoiceLines').appendChild(nouvelleLigne);
        }
    </script>


@endsection
