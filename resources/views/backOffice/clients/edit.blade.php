@extends("backOffice.layout.panel")


@section("title","Ajouter Fournisseur")

@section("style_links")
@endsection

@section("style")
@endsection




@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Modifier Client</h3>
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
                            <form method="post" action="{{ route('clients.update', $client->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="provider-infos">
                                    <span class="section">Informations Client</span>
                                    @if(!$client->entreprise_yn)
                                        <div id="formPersonne">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom Complet</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ $client->name }}" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" class="form-control" value="{{ $client->phone }}" data-validate-length-range="6" data-validate-words="2" name="phone"  placeholder="ex. 0606060606"  />
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div  id="formEntreprise">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Entreprise</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ $client->name }}" data-validate-length-range="6" data-validate-words="2" name="nom_entreprise"  placeholder="ex. IPEP"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">ICE</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $client->ICE }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="ICE"  placeholder="ICE"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nom du conducteur</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $client->driver_name }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="driver_name"  placeholder="Driver name"  />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Numero de contacte</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $client->phone }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="phone_contact"  placeholder="ex. 0509002324"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(!$client->user)
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                            <div class="col-md-6 col-sm-6">
                                                <p style="padding: 5px;">
                                                    <input type="checkbox" name="nouveau_compte" id="addAccountCheckbox" value="new_account" data-parsley-mincheck="2" class="flat" /> Ajouter un compte pour ce fournisseur
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
                                    @endif
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('clients.index')}}" class="btn btn-secondary">Annuler</a>
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


@endsection
