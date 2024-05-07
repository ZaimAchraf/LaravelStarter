@extends("backOffice.layout.panel")


@section("title","Modifier Facture")

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
                    <h3>Editer Facture</h3>
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
                            <form method="post" action="{{ route('invoices.update')}}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="invoiceID" value="{{$invoice->id}}">
{{--                                <span class="section">User Info</span>--}}
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Client</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->quotation->client->name }}" data-validate-length-range="6" data-validate-words="2" name="" disabled />
                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Véhicule</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $invoice->quotation->vehicle->label }}" data-validate-length-range="6" data-validate-words="2" name=""  disabled />
                                    </div>
                                </div>

                                <span class="section">Informations de Facture</span>

                                <div id="lignesDevis">
                                    @foreach($invoice->invoiceLines as $i => $line)
                                        <div class="invoiceLine {{$i != 0 ? 'invoiceLine-new' : ''}}">
                                            <input type="hidden" value="{{ $line->id }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][id]" />

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" value="{{ $line->description }}" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][description]"  />
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="lines[{{$i}}][type]" id="type" class="form-control">
                                                        <option value="null" {{ $line->type == null ? 'selected' : '' }}>Selectionner le type</option>
                                                        <option value="Product" {{ $line->type == 'Product' ? 'selected' : '' }}>Produit</option>
                                                        <option value="MOD" {{ $line->type == 'MOD' ? 'selected' : '' }}>MOD</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="lines[{{$i}}][state]" id="state" class="form-control">
                                                        <option value="null" {{ $line->state == null ? 'selected' : '' }}>Selectionner l'état</option>
                                                        <option value="Occasion" {{ $line->state == 'Occasion' ? 'selected' : '' }}>Occasion</option>
                                                        <option value="Nouveau" {{ $line->state == 'Nouveau' ? 'selected' : '' }}>Neuf</option>
                                                        <option value="Adaptable" {{ $line->state == 'Adaptable' ? 'selected' : '' }}>Adaptable</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $line->quantity }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Prix Unitaire</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $line->price }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][price]" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">TVA (%)</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" value="{{ $line->TVA }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][TVA]" />
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                                                <div class="col-md-6 col-sm-6 d-flex ">
                                                    <button type="button" class="btn btn-danger" onclick="deleteLineBackend(event, {{ $i }})"><i class="fa fa-trash-o"></i></button>
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
                                        <div class="col-md-6 offset-md-3  pt-2">1`````````
                                            <button type='submit' class="btn btn-primary">Submit</button>
                                            <a href="{{route('invoices.index')}}" class="btn btn-secondary">Annuler</a>
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

        function deleteLineBackend(event, index) {

            event.preventDefault();

            var lineId = $("input[name='lines[" + index + "][id]']").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette ligne de facture ?");

            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: '/invoices/deleteLine',
                    data: {
                        _token: csrfToken,
                        line_id: lineId
                    },
                    success: function(response) {
                        // Suppression réussie, vous pouvez mettre à jour l'interface si nécessaire
                        $(".invoiceLine:eq(" + index + ")").remove();
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

        document.getElementById('addDevisLine').addEventListener('click', ajouterLigneDevis);
        let linesNumber = document.querySelectorAll(".invoiceLine").length;
        function ajouterLigneDevis(e) {

            e.preventDefault();

            const nouvelleLigne = document.createElement('div');
            nouvelleLigne.classList.add('invoiceLine');
            nouvelleLigne.classList.add('invoiceLine-new');
            nouvelleLigne.innerHTML = `
                </hr>
                <input type="hidden" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][id]" />
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Description</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[${linesNumber}][description]"  placeholder="ex. PARE CHOC AV" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Type</label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][type]" id="type" class="form-control">
                            <option value="null" selected>Selectionner le type</option>
                            <option value="Product">Produit</option>
                            <option value="MOD">MOD</option>
                        </select>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Etat</label>
                    <div class="col-md-6 col-sm-6">
                        <select name="lines[${linesNumber}][state]" id="state" class="form-control" >
                            <option value="null" selected >Selectionner l'etat</option>
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
                        <button type="button" class="btn btn-danger" onclick="deleteLine(event)"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
            `;

            linesNumber++;

            document.getElementById('lignesDevis').appendChild(nouvelleLigne);
        }
    </script>

@endsection
