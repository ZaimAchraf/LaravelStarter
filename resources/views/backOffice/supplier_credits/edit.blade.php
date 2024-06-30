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
                    <h3>Editer Crédit</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <form method="post" action="{{ route('supplierCredits.update', $supplierCredit->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Montant payé<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" data-validate-length-range="6" data-validate-words="2" name="paid" value="{{ $supplierCredit->paid }}" required="required" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Commentaire<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="2" name="comment" />
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Document</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input id="picture" type="file" name="documents" multiple>
                                    </div>
                                </div>
                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <button type='submit' class="btn btn-primary">Modifier</button>
                                            <a href="{{route('supplierCredits.index')}}" class="btn btn-secondary">Annuler</a>
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
            console.log(roleSelect)
            console.log(additionalFields)

            roleSelect.addEventListener('change', function() {
                console.log(roleSelect.value)
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
