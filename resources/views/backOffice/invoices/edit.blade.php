@extends("backOffice.layout.panel")


@section("title","Modifier Facture")

@section("style")
    <style>
        .invoiceLine-new {
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

@section("style_links")
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Editer Facture</h3>
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
                            <form method="post" action="{{ route('invoices.update')}}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="invoiceID" value="{{$invoice->id}}">
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->folder->client->name }}" data-validate-length-range="6" data-validate-words="2" name="" disabled />
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Véhicule</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->folder->vehicle->label }}" data-validate-length-range="6" data-validate-words="2" name=""  disabled />
                                    </div>
                                </div>

                                <span class="section">Informations de Facture</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Numero</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->number }}" data-validate-length-range="6" data-validate-words="2" name="number"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->title }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Date Facture</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" class="form-control" value="{{$invoice->invoice_date}}" data-validate-length-range="6" data-validate-words="2" name="invoice_date"/>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Règlement</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" placeholder="e.g : reglement1/reglemet2..." value="{{$invoice->payments}}"  data-validate-length-range="6" data-validate-words="2" name="payments"/>
                                        <hr>
                                    </div>
                                </div>
                                <span class="section">Lignes de Facture</span>

                                <div id="lignesDevis">
                                    @foreach($invoice->invoiceLines as $i => $line)
                                        <input type="hidden" value="{{ $line->id }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][id]" />


                                        <div class="invoiceLine">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input readonly class="form-control" value="{{ $line->type }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][type]" />
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input readonly class="form-control" value="{{ $line->description }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][description]"  placeholder="ex. PARE CHOC AV" />
                                                </div>
                                            </div>

                                            @if($line->type != 'MOD')
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input readonly class="form-control" value="{{ $line->state }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][state]"  />
                                                    </div>
                                                </div>
                                                @if($line->reference)
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Reference</label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input readonly class="form-control" value="{{ $line->reference }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][reference]"  />
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" value="{{ $line->quantity }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]"  />
                                                    </div>
                                                </div>
                                            @endif



                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $line->price }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][price]"  placeholder="" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $line->TVA }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][TVA]"  placeholder="" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6 d-flex ">
                                                    <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLineBackend(event, {{$i}})"><i class="fa fa-trash-o"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                        <button id="addDevisLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de Facture</button>
                                    </div>
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">1`````````
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('folders.show', $invoice->folder->id)}}" class="btn btn-secondary">Annuler</a>
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
            $('.exist_product').select2({
                placeholder: "Selectionner Produit",
                allowClear: true
            });
        });
    </script>

    <script>
        function deleteLine(event) {
            // Supprimer la ligne de devis avec l'index donné
            event.preventDefault();

            var parentDiv = event.target.closest('.invoiceLine'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
            }
        }

        function deleteLineBackend(event, index) {

            event.preventDefault();

            var lineId = $("input[name='lines[" + index + "][id]']").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette ligne de facture ?");

            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: '/invoices/deleteLine',
                    data: {
                        _token: csrfToken,
                        line_id: lineId
                    },
                    success: function(response) {
                        // Suppression réussie, vous pouvez mettre à jour l'interface si nécessaire
                        $(".invoiceLine:eq(" + index + ")").remove();
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
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
        //
        // function toggleNewProvider(event) {
        //     let NewProviderCheckbox = event.target;
        //     let invoiceLine = $(NewProviderCheckbox).closest('.invoiceLine');
        //     let providerForm = invoiceLine.find('.new-provider-form');
        //
        //     if (NewProviderCheckbox.checked) {
        //         providerForm.show();
        //     } else {
        //         providerForm.hide();
        //     }
        // }

        function handleSelectChange(event) {
            let selectBox = event.target;
            let invoiceLine = $(selectBox).closest('.invoiceLine');
            let productFields = invoiceLine.find('.product-fields');
            // let providerFields = invoiceLine.find('.provider-infos');
            let modFields = invoiceLine.find('.mod-fields');
            let selectedValue = selectBox.value;

            if (selectedValue === "Produit") {
                productFields.show();
                // providerFields.show();
                modFields.hide();
            } else {
                productFields.hide();
                // providerFields.hide();
                modFields.show();
            }
        }
    </script>

    {{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addDevisLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = document.querySelectorAll(".invoiceLine").length;
        function ajouterLigneDevis(e) {

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

            document.getElementById('lignesDevis').appendChild(nouvelleLigne);

            $('.exist_product').select2({
                placeholder: "Selectionner Produit",
                allowClear: true
            });
        }
    </script>

@endsection
