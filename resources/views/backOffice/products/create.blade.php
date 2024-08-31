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
                    <h3>Ajouter un produit</h3>
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
                            <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Libelle</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ old('label') }}" data-validate-length-range="6" data-validate-words="2" name="label"  placeholder="Label..."  />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Référence</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" value="{{ old('ref') }}" data-validate-length-range="6" data-validate-words="2" name="ref"  placeholder="Reference..."  />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Qte</label>
                                    <div class="col-md-6 col-sm-6">
                                            <input type="number" class="form-control" value="0" data-validate-length-range="6" data-validate-words="2" name="Qte"  placeholder="Qte..."  />
                                    </div>
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('products.index')}}" class="btn btn-secondary">Annuler</a>
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
