@extends("backOffice.layout.panel")


@section("title","Créer une commande")

@section("style_links")
@endsection

@section("style")
    <style>
        .order-line-new {
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
                    <h3>Créer Commande</h3>
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
                            <form method="post" action="{{ route('orders.update', $order->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" value="{{ $order->provider->name }}" data-validate-length-range="6" data-validate-words="2" name="name"  />
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Commande</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre de commande</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $order->title }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div id="order-lines">
                                    @foreach($order->orderLines as $i => $ol)
                                        <input type="hidden" value="{{ $ol->id }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][id]" />
                                        <div class="order-line {{$i != 0 ? 'order-line-new' : ''}}">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[{{$i}}][exist_product]" id="exist_product" class="form-control exist_product" >
                                                    <option value="" selected disabled>Selectionner Produit Existant</option>
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}" {{ $product->id == $ol->product->id ? 'selected' : '' }}>
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
                                                    <input type="checkbox" name="lines[{{$i}}][new_product]" id="new-product-check" value="new_product" class="flat new-product-check" /> Nouveau produit
                                                </p>
                                            </div>
                                        </div>

                                        <div class="new-product-form" style="display: none">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][label]"  placeholder="" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][ref]" />
                                                </div>
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
                                                <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLineBackend(event, {{ $i }})"><i class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                        <button id="addOrderLine" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter une ligne de commande</button>
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

            var parentDiv = event.target.closest('.order-line'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
            }
        }

        function deleteLineBackend(event, index) {
            event.preventDefault();

            var lineId = $("input[name='lines[" + index + "][id]']").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette ligne de commande ?");

            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: '/orders/deleteLine',
                    data: {
                        _token: csrfToken,
                        line_id: lineId
                    },
                    success: function(response) {
                        // Suppression réussie, vous pouvez mettre à jour l'interface si nécessaire
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }
    </script>

{{--    ajouter ligne devis on click--}}
    <script>

        document.getElementById('addOrderLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = document.querySelectorAll(".order-line").length - 1;
        function ajouterLigneDevis(e) {

            e.preventDefault();
            linesNumber++;

            const nouvelleLigne = document.createElement('div');
            nouvelleLigne.classList.add('order-line');
            nouvelleLigne.classList.add('order-line-new');
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
                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                    <div class="col-md-6 col-sm-6 d-flex ">
                        <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
            `;


            document.getElementById('order-lines').appendChild(nouvelleLigne);

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
