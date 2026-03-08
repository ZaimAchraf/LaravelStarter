@extends("backOffice.layout.panel")


@section("title","Créer Utilisateur")

@section("style_links")
@endsection

@section("script_links")

@endsection




@section("content-wrapper")

    <div class="right_col" role="main">

        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Créer Utilisateur</h3>
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
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="phone"  placeholder="ex. 0606060606"  />
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
                                            <option value="3">User</option>
                                            <option value="2">Admin</option>
                                            <option value="1">Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Picture</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="image view view-first" style="width: 100px; display: block; height: 100px">
                                            <img id="picturePreview" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; border-radius: 4px;" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Crect fill='%23f0f0f0' width='100' height='100'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-size='12' fill='%23999'%3ENo Image%3C/text%3E%3C/svg%3E" alt="No Image" />
                                            <div class="mask" >
                                                <div class="tools tools-bottom">
                                                    <label for="picture" style="cursor: pointer"><i class="fa fa-pencil"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input id="picture" type="file" name="picture" accept="image/*" style="display: none;" >
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

            // File upload preview
            const fileInput = document.getElementById('picture');
            const pictureLabel = document.querySelector('label[for="picture"]');
            const picturePreview = document.getElementById('picturePreview');

            if (pictureLabel) {
                pictureLabel.addEventListener('click', function(e) {
                    e.preventDefault();
                    fileInput.click();
                });
            }

            // Preview image before upload
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        picturePreview.src = event.target.result;
                        picturePreview.style.objectFit = 'cover';
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
