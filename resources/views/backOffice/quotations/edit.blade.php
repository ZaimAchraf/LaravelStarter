@extends("backOffice.layout.panel")


@section("title","Modifier Utilisateur")

@section("style")
    <style>
        .ligneDevis-new {
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
                    <h3>Editer Devis</h3>
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
                            <form method="post" action="{{ route('quotations.update', $quotation->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $quotation->client->name }}" data-validate-length-range="6" data-validate-words="2" name="" disabled />
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Véhicule</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $quotation->vehicle->label }}" data-validate-length-range="6" data-validate-words="2" name=""  disabled />
                                    </div>
                                </div>

                                <span class="section">Informations de Devis</span>

                                <div id="lignesDevis">
                                    @foreach($quotation->quotationLines as $i => $ql)
                                        <div class="ligneDevis {{$i != 0 ? 'ligneDevis-new' : ''}}">
                                            <input type="hidden" value="{{ $ql->id }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][id]" />

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input disabled class="form-control" value="{{ $ql->description }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][description]"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input disabled class="form-control" value="{{ $ql->type }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][type]"  />
                                                </div>
                                            </div>
                                            @if($ql->type != 'MOD')
                                                @if($ql->reference)
                                                    <div class="field item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Reference</label>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input disabled type="text" value="{{ $ql->reference }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][reference]" />
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input disabled class="form-control" value="{{ $ql->state }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][state]"  />
                                                    </div>
                                                </div>

                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" value="{{ $ql->quantity }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]" />
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $ql->price }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][price]" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $ql->TVA }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][TVA]" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6 d-flex ">
                                                    <button type="button" class="btn btn-danger" onclick="deleteLineBackend(event, {{ $i }})"><i class="fa fa-trash-o"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                        <button id="addDevisLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de Devis</button>
                                    </div>
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">1`````````
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('quotations.index')}}" class="btn btn-secondary">Annuler</a>
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
            let modFields = ligneDevis.find('.mod-fields');
            let selectedValue = selectBox.value;

            if (selectedValue === "Produit") {
                productFields.show();
                modFields.hide();
            } else {
                productFields.hide();
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

        function deleteLineBackend(event, index) {
            event.preventDefault();

            var lineId = $("input[name='lines[" + index + "][id]']").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Afficher une boîte de dialogue de confirmation
            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette ligne de devis ?");

            // Vérifier si l'utilisateur a confirmé la suppression
            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: '/quotations/deleteLine',
                    data: {
                        _token: csrfToken,
                        line_id: lineId
                    },
                    success: function(response) {
                        // Suppression réussie, vous pouvez mettre à jour l'interface si nécessaire
                        $(".ligneDevis:eq(" + index + ")").remove();
                    },
                    error: function(xhr, status, error) {
                        // Gérer les erreurs en cas d'échec de la suppression
                        console.error(error);
                    }
                });
            }
        }

    </script>

    {{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addDevisLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = document.querySelectorAll(".ligneDevis").length;
        function ajouterLigneDevis(e) {

            e.preventDefault();

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
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6 d-flex ">
                        <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
`;

            linesNumber++;

            document.getElementById('lignesDevis').appendChild(nouvelleLigne);
        }
    </script>

@endsection
