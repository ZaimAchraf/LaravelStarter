@extends("backOffice.layout.panel")


@section("title","Modifier Utilisateur")

@section("style_links")
@endsection

@section("script_links")

@endsection




@section("content-wrapper")

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Creer Utilisateur</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Nom Complet<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input class="form-control" data-validate-length-range="6" data-validate-words="2" name="name"  placeholder="ex. John f. Kennedy" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Nom  d'utilisateur<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('username')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="username"  required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Email<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="email" class="form-control" data-validate-length-range="6" data-validate-words="2" name="email"  placeholder="ex. exemple@exemple.com" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Password<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="password" class="form-control" data-validate-length-range="6" data-validate-words="2" name="password" placeholder="********" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Confirmer Password<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="password" class="form-control" data-validate-length-range="6" data-validate-words="2" name="password_confirmation" placeholder="********" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="phone"  placeholder="ex. 0606060606" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Adresse</label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('adresse')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="adresse"  placeholder="" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Gendre<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="gendre" id="gendre" class="form-control" required>
                                            <option value="H" selected>Homme</option>
                                            <option value="F">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('role_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <select name="role_id" id="role" class="form-control" required>
                                            <option disabled selected value="">Select Role</option>
                                            <option value="4">Employee</option>
                                            <option value="3">Client</option>
                                            <option value="2">Admin</option>
                                            <option value="1">Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="additional_fields" style="display: none;">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fonction<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            @error('fonction')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <input type="text" class="form-control" name="fonction" placeholder="ex. Manager" />
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Salaire<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            @error('salaire')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <input type="text" class="form-control" name="salaire" placeholder="ex. 5000" />
                                        </div>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Picture<span class="required"></span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input id="picture" type="file" name="images" multiple>
                                    </div>
                                </div>
                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('users.index')}}" class="btn btn-secondary">Annuler</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const additionalFields = document.getElementById('additional_fields');

            roleSelect.addEventListener('change', function() {
                if (roleSelect.value === '2' || roleSelect.value === '4') {
                    additionalFields.style.display = 'block';
                } else {
                    additionalFields.style.display = 'none';
                }
            });

            // Vérifier l'état initial du champ de rôle au chargement de la page
            if (roleSelect.value === '2' || roleSelect.value === '4') {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
            }
        });
    </script>
@endsection
