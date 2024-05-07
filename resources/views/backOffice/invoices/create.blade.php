@extends("backOffice.layout.panel")


@section("title","Creer un Devis")

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

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('invoices.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{$quotation->id}}" name="quotation">
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input disabled class="form-control" value="{{ $quotation->client->name }}" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy"  />
                                        </div>
                                    </div>
                                </div>

                                <div class="vehicule">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Véhicule</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input disabled class="form-control" value="{{ $quotation->vehicle->label }}" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy"  />
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Facture</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('title') }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div id="invoiceLines">
                                    @foreach($quotation->quotationLines as $i => $ql)
                                    <div class="invoiceLine">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[{{$i}}][type]" id="type" class="form-control" >
                                                    <option value="null" {{ $ql->type == null ? 'selected' : '' }} disabled >Selectionner le type</option>
                                                    <option value="Produit" {{ $ql->type == 'Product' ? 'selected' : '' }}>Produit</option>
                                                    <option value="MOD" {{ $ql->type == 'MOD' ? 'selected' : '' }}>MOD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ $ql->description }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][description]"  placeholder="ex. PARE CHOC AV" />
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[{{$i}}][state]" id="state" class="form-control" >
                                                    <option value="null" {{ $ql->state == null ? 'selected' : '' }}>Selectionner l'état</option>
                                                    <option value="Occasion" {{ $ql->state == 'Occasion' ? 'selected' : '' }}>Occasion</option>
                                                    <option value="Nouveau" {{ $ql->state == 'Nouveau' ? 'selected' : '' }}>Neuf</option>
                                                    <option value="Adaptable" {{ $ql->state == 'Adaptable' ? 'selected' : '' }}>Adaptable</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ $ql->quantity }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]"  />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ $ql->price }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][price]"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ $ql->TVA }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][TVA]"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6 d-flex ">
                                                <button type="button" class="btn btn-danger deleteLineBtn" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
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
                                        <div class="col-md-6 offset-md-3  pt-2">
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
        function deleteLine(event) {
            // Supprimer la ligne de devis avec l'index donné
            event.preventDefault();

            var parentDiv = event.target.closest('.invoiceLine'); // Recherche le parent avec la classe 'teste'

            if (parentDiv) {
                parentDiv.remove(); // Supprime le parent si trouvé
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
                        <select name="lines[${linesNumber}][type]" id="type" class="form-control" >
                            <option value="" selected disabled>Selectionner le type</option>
                            <option value="Produit" >Produit</option>
                            <option value="MOD">MOD</option>
                        </select>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][description]"  placeholder="ex. PARE CHOC AV" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][state]" id="state" class="form-control" >
                            <option value="null" selected >Selectionner l'etat</option>
                            <option value="Occasion" >Occasion</option>
                            <option value="Nouveau">Nouveau</option>
                        </select>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][quantity]"  />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                    <div class="col-md-6 col-sm-6">
                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][price]"  placeholder="" />
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

    <script>
        function toggleUserInfos() {
            var checkbox = document.getElementById('addAccountCheckbox');
            var userInfosDiv = document.querySelector('.user-infos');

            if (checkbox.checked) {
                userInfosDiv.style.display = 'block';
            } else {
                userInfosDiv.style.display = 'none';
            }
        }

        var checkbox = document.getElementById('addAccountCheckbox');
        checkbox.addEventListener('change', toggleUserInfos);

        toggleUserInfos();
    </script>

    <script>
        function toggleNewClientForm() {

            var clientExistant = document.getElementById('exist_client');
            var newClientCheckbox = document.getElementById('nouveau-client-check');
            var newClientForm = document.getElementById('nouveau-client-form');
            var newVehicleForm = document.getElementById('new-vehicle-form');
            var newVehicleCheckbox = document.getElementById('new-vehicle-check');


            newVehicleCheckbox.checked = newClientCheckbox.checked;

            if (newClientCheckbox.checked) {
                newClientForm.style.display = 'block';
                clientExistant.disabled = true;
                newVehicleForm.style.display = 'block'
            } else {
                newClientForm.style.display = 'none';
                clientExistant.disabled = false;
                newVehicleForm.style.display = 'none'
            }
        }

        var newClientCheckbox = document.getElementById('nouveau-client-check');
        newClientCheckbox.addEventListener('change', toggleNewClientForm);

        toggleNewClientForm();
    </script>

    <script>
        document.getElementById('exist_client').addEventListener('change', function() {
            var vehiclesSelect = document.getElementById('exist_vehicle');
            var selectedOption = this.options[this.selectedIndex];
            var vehiclesData = JSON.parse(selectedOption.getAttribute('data-vehicles'));

            vehiclesSelect.innerHTML = ''; // Supprimer les anciennes options
            vehiclesData.forEach(function(vehicle) {
                var option = document.createElement('option');
                option.value = vehicle.id; // Assurez-vous que vous avez un attribut id pour chaque véhicule
                option.text = vehicle.label; // Changer cela selon la structure de vos données de véhicule
                vehiclesSelect.appendChild(option);
            });
        });
        function toggleNewVehicleForm() {

            var newVehicleCheckbox = document.getElementById('new-vehicle-check');
            var newVehicleForm = document.getElementById('new-vehicle-form');
            var vehiclesSelect = document.getElementById('exist_vehicle');

            if (newVehicleCheckbox.checked) {
                newVehicleForm.style.display = 'block';
                vehiclesSelect.disabled = true;
            } else {
                newVehicleForm.style.display = 'none';
                vehiclesSelect.disabled = false;
            }
        }

        var newVehicleCheckbox = document.getElementById('new-vehicle-check');
        newVehicleCheckbox.addEventListener('change', toggleNewVehicleForm);

        toggleNewClientForm();
    </script>

    <script>
        function showFormEntreprise(e) {
            e.preventDefault();
            var formEntreprise = document.getElementById('formEntreprise');
            var formPersonne = document.getElementById('formPersonne');
            var btnEntreprise = document.getElementById('btn-entreprise');
            var btnPersonne = document.getElementById('btn-personne');

            formEntreprise.style.display = 'block';
            formPersonne.style.display = 'none';

            btnEntreprise.classList.add('active');
            btnPersonne.classList.remove('active');
        }

        function showFormPersonne(e) {
            e.preventDefault();
            var formEntreprise = document.getElementById('formEntreprise');
            var formPersonne = document.getElementById('formPersonne');
            var btnEntreprise = document.getElementById('btn-entreprise');
            var btnPersonne = document.getElementById('btn-personne');

            formEntreprise.style.display = 'none';
            formPersonne.style.display = 'block';

            btnEntreprise.classList.remove('active');
            btnPersonne.classList.add('active');
        }

        var btnEntreprise = document.getElementById('btn-entreprise');
        btnEntreprise.addEventListener('click', showFormEntreprise);

        var btnPersonne = document.getElementById('btn-personne');
        btnPersonne.addEventListener('click', showFormPersonne);

        showFormPersonne();
    </script>


@endsection
