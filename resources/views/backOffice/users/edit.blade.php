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
                    <h3>Editer Utilisateur</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Erreurs de validation:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Nom Complet<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" data-validate-length-range="6" data-validate-words="2" name="name" value="{{ $user->name }}" placeholder="ex. John f. Kennedy" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Nom  d'utilisateur<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="username" value="{{ $user->username }}" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Email<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="email" class="form-control" data-validate-length-range="6" data-validate-words="2" name="email" value="{{ $user->email }}" placeholder="ex. exemple@exemple.com" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Telephone</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="phone" value="{{ $user->phone }}" placeholder="ex. 0606060606" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Gendre<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        @error('gendre')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <select name="gendre" id="sexe" class="form-control" required>
                                            <option value="4" {{$user->sexe == 'H' ? 'selected' : ''}}>Homme</option>
                                            <option value="3" {{$user->sexe == 'F' ? 'selected' : ''}}>Femme</option>
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
                                            <option value="3" {{$user->role->id == 3 ? 'selected' : ''}}>Client</option>
                                            <option value="2" {{$user->role->id == 2 ? 'selected' : ''}}>Admin</option>
                                            <option value="1" {{$user->role->id == 1 ? 'selected' : ''}}>Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="image view view-first" style="width: 100px; display: block; height: 100px">
                                            <img id="picturePreview" style="width: 100%; height: 100%; display: block; object-fit: cover; border-radius: 4px;" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
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
