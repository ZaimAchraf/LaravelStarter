@extends("backOffice.layout.panel")


@section("title","Creer un Devis")

@section("style_links")
@endsection

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
                    <h3>Creer Bon de Livraison</h3>
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
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="client" id="exist_client" class="form-control" >
                                                <option value="{{$quotation->client->id}}" selected disabled >
                                                    {{$quotation->client->name}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="vehicule">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Vehicule</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="vehicle" id="exist_vehicle" class="form-control" >
                                                <option value="{{$quotation->vehicle->name}}" selected disabled>
                                                    {{$quotation->vehicle->name}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Devis</span>

                                <div id="lignesDevis">
                                    <?php
                                        $index = 0;
                                    ?>
                                    @foreach($quotation->quotationLines as $ql)
                                        <div class="ligneDevis">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('description') }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$index}}][description]"  placeholder="ex. PARE CHOC AV" />
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="lines[{{$index}}][state]" id="state" class="form-control" >
                                                        <option value="" selected>Selectionner l'etat</option>
                                                        <option value="Occasion" >Occasion</option>
                                                        <option value="Nouveau">Nouveau</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$index}}][quantity]"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('price') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$index}}][price]"  placeholder="" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('TVA') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$index}}][TVA]"  placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            $index++;
                                        ?>
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
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][description]"  placeholder="ex. PARE CHOC AV" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][state]" id="state" class="form-control" >
                            <option value="" selected>Selectionner l'etat</option>
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
`;


            document.getElementById('lignesDevis').appendChild(nouvelleLigne);
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
