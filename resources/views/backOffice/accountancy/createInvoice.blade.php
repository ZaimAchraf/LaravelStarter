@extends("backOffice.layout.panel")


@section("title","Créer une commande")

@section("style_links")
@endsection

@section("style")
    <style>
        .orderLine-new {
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
                    <h3>Créer Facture</h3>
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

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert" id="myAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('accountants.storeInvoice') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="provider-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="exist_provider" id="exist_provider" class="form-control" >
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
                                                <input type="checkbox" name="new_provider" id="nouveau-provider-check" value="Nouveau" class="flat" /> Nouveau Fournisseur
                                            </p>
                                        </div>
                                    </div>
                                    <div class="" id="nouveau-provider-form">
                                        <span class="section">Informations Fournisseur</span>
                                        <div id="formPersonne">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom </label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('name') }}" data-validate-length-range="6" data-validate-words="2" name="name"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" class="form-control" value="{{ old('phone') }}" data-validate-length-range="6" data-validate-words="2" name="phone"  placeholder="ex. 0606060606"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Facture</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre de facture</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('title') }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">TVA(%) : </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('TVA') }}" data-validate-length-range="6" data-validate-words="2" name="TVA"/>
                                        <hr>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Document</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input id="picture" type="file" name="document">
                                    </div>
                                </div>

                                <div id="orderLines">
                                    <div class="orderLine">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[0][exist_product]" id="exist_product" class="form-control exist_product" >
                                                    <option value="" selected disabled>Selectionner Produit Existant</option>
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}">
                                                            {{$product->label . ' - ' . $product->ref}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6">
                                                <p style="padding: 5px;">
                                                    <input type="checkbox" name="lines[0][new_product]" id="new-product-check" value="new_product" class="flat new-product-check" /> Nouveau produit
                                                </p>
                                            </div>
                                        </div>

                                        <div class="new-product-form" style="display: none">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][label]"  placeholder="" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('ref') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][ref]" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][quantity]"  />
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Prix</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ old('price') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][price]"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                        <button id="addOrderLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de facture</button>
                                    </div>
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('orders.index')}}" class="btn btn-secondary">Annuler</a>
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

            var parentDiv = event.target.closest('.orderLine'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
            }
        }
    </script>

{{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addOrderLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = 0;
        function ajouterLigneDevis(e) {

            e.preventDefault();
            linesNumber++;

            const nouvelleLigne = document.createElement('div');
            nouvelleLigne.classList.add('orderLine');
            nouvelleLigne.classList.add('orderLine-new');
            var selectContent = document.querySelectorAll('.exist_product')[0].innerHTML;
            nouvelleLigne.innerHTML = `
                </hr>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][exist_product]" id="exist_product" class="form-control exist_product" >
                            ${selectContent}
                        </select>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6">
                        <p style="padding: 5px;">
                            <input type="checkbox" name="lines[${linesNumber}][new_product]" id="new-product-check" value="new_product" class="flat new-product-check" /> Nouveau produit
                        </p>
                    </div>
                </div>

                <div class="new-product-form" style="display: none">
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][label]"  placeholder="" />
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" value="{{ old('ref') }}" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][ref]" />
                        </div>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][quantity]"  />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Prix</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" value="{{ old('price') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][price]"  />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6 d-flex ">
                        <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
            `;


            document.getElementById('orderLines').appendChild(nouvelleLigne);

            var newProductCheckbox = document.querySelectorAll('.new-product-check');
            newProductCheckbox.forEach(el =>
            {
                el.addEventListener('change', toggleNewProductForm);
            });

            toggleNewProductForm();
        }
    </script>

    <script>
        function toggleNewProviderForm() {

            var existingProvider = document.getElementById('exist_provider');
            var newProviderCheckbox = document.getElementById('nouveau-provider-check');
            var newProviderForm = document.getElementById('nouveau-provider-form');
            var newProductForm = document.querySelectorAll('.new-product-form');
            var newProductCheckbox = document.querySelectorAll('.new-product-check');


            if (newProviderCheckbox.checked) {
                newProviderForm.style.display = 'block';
                existingProvider.disabled = true;
                newProductForm.forEach((el, index) => {
                    el.style.display = 'block';
                    newProductCheckbox[index].checked = newProviderCheckbox.checked;
                });
            } else {
                newProviderForm.style.display = 'none';
                existingProvider.disabled = false;
                newProductForm.forEach((el, index) => {
                    el.style.display = 'none';
                    newProductCheckbox[index].checked = newProviderCheckbox.checked;
                });
            }
        }

        var newProviderCheckbox = document.getElementById('nouveau-provider-check');
        newProviderCheckbox.addEventListener('change', toggleNewProviderForm);

        toggleNewProviderForm();
    </script>

    <script>

        function toggleNewProductForm() {
            var newProductCheckboxes = document.querySelectorAll('.new-product-check');
            var newProductForms = document.querySelectorAll('.new-product-form');
            var productSelects = document.querySelectorAll('.exist_product');

            newProductCheckboxes.forEach((checkbox, index) => {
                if (checkbox.checked) {
                    newProductForms[index].style.display = 'block';
                    productSelects[index].disabled = true;
                } else {
                    newProductForms[index].style.display = 'none';
                    productSelects[index].disabled = false;
                }
            });
        }

        var newProductCheckbox = document.querySelectorAll('.new-product-check');
        newProductCheckbox.forEach(el =>
        {
            el.addEventListener('change', toggleNewProductForm);
        });

        toggleNewProductForm();
    </script>


@endsection
