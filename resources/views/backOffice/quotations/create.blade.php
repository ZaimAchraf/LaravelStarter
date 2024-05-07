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
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="exist_client" id="exist_client" class="form-control" >
                                                <option value="" selected disabled>Selectionner Client</option>
                                                @foreach($clients as $client)
                                                    <option value="{{$client->id}}" data-vehicles="{{ json_encode($client->vehicles) }}">
                                                        {{$client->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <p style="padding: 5px;">
                                                <input type="checkbox" name="new_client" id="nouveau-client-check" value="Nouveau" class="flat" /> Nouveau Client
                                            </p>
                                        </div>
                                    </div>
                                    <div class="" id="nouveau-client-form">
                                        <div class="field item form-group d-flex justify-content-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-success mr-2 active" id="btn-personne">Personne</button>
                                                <button class="btn btn-warning" id="btn-entreprise">Entreprise</button>
                                            </div>
                                        </div>
                                        <span class="section">Informations Client</span>
                                        <div id="formPersonne">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom Complet</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('name') }}" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" class="form-control" value="{{ old('phone') }}" data-validate-length-range="6" data-validate-words="2" name="phone"  placeholder="ex. 0606060606"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <p style="padding: 5px;">
                                                        <input type="checkbox" name="nouveau_compte" id="addAccountCheckbox" value="new_account" data-parsley-mincheck="2" class="flat" /> Ajouter un compte pour le client
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="user-infos" style="display: none;">
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Nom  d'utilisateur</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" value="{{ old('username') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="username"   />
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Email</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="email" value="{{ old('email') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="email"  placeholder="ex. exemple@exemple.com"  />
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Password</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="password" class="form-control" data-validate-length-range="6" data-validate-words="2" name="password" placeholder="********"  />
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Confirmer Password</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="password" class="form-control" data-validate-length-range="6" data-validate-words="2" name="password_confirmation" placeholder="********"  />
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Adresse</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" value="{{ old('adresse') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="adresse"  placeholder=""  />
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Gendre</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select name="gendre" id="gendre" class="form-control" >
                                                            <option value="H" selected>Homme</option>
                                                            <option value="F">Femme</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: none;" id="formEntreprise">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Entreprise</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ old('nom_entreprise') }}" data-validate-length-range="6" data-validate-words="2" name="nom_entreprise"  placeholder="ex. IPEP"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">ICE</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('ICE') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="ICE"  placeholder="ICE"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Driver Name</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('driver_name') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="driver_name"  placeholder="Driver name"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Numero de contacte</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ old('phone_contact') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="phone_contact"  placeholder="ex. 0509002324"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations Vehicule</span>

                                <div class="vehicule">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="exist_vehicle" id="exist_vehicle" class="form-control" >
                                                <option value="" selected disabled>Selectionner Vehicule Existante</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <p style="padding: 5px;">
                                                <input type="checkbox" name="new_vehicle" id="new-vehicle-check" value="new_vehicle" class="flat" /> Nouvelle vehicule
                                            </p>
                                        </div>
                                    </div>
                                    <div id="new-vehicle-form" style="display: none">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Libelle</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="label"  placeholder="ex. Volvo"  />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Immatricule</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('registration') }}" data-validate-length-range="6" data-validate-words="2" name="registration"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Assurance</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('insurance') }}" data-validate-length-range="6" data-validate-words="2" name="insurance"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Numero police</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('police_number') }}" data-validate-length-range="6" data-validate-words="2" name="police_number"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Kilométrage</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('mileage') }}" data-validate-length-range="6" data-validate-words="2" name="mileage"  placeholder="" />
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Numero Chassis</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('chassis_number') }}" data-validate-length-range="6" data-validate-words="2" name="chassis_number"  placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Devis</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre de Devis</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('title') }}" data-validate-length-range="6" data-validate-words="2" name="title"/>
                                        <hr>
                                    </div>
                                </div>

                                <div id="lignesDevis">
                                    <div class="ligneDevis">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[0][type]" id="type" class="form-control" >
                                                    <option value="" selected disabled >Selectionner le type</option>
                                                    <option value="Produit" >Produit</option>
                                                    <option value="MOD">MOD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{ old('description') }}" data-validate-length-range="6" data-validate-words="2" name="lines[0][description]"  placeholder="ex. PARE CHOC AV" />
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="lines[0][state]" id="state" class="form-control" >
                                                    <option value="null" selected disabled>Selectionner l'état</option>
                                                    <option value="Occasion" >Occasion</option>
                                                    <option value="Nouveau">Neuf</option>
                                                    <option value="Adaptable">Adaptable</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Quantite</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ old('quantity') }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[0][quantity]"  />
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
                        <select name="lines[0][state]" id="state" class="form-control" >
                            <option value="null" selected disabled>Selectionner l'état</option>
                            <option value="Occasion" >Occasion</option>
                            <option value="Nouveau">Neuf</option>
                            <option value="Adaptable">Adaptable</option>
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
