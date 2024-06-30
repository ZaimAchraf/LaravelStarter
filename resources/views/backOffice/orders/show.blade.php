@extends("backOffice.layout.panel")


@section("title","Créer une commande")

@section("style_links")
@endsection

@section("style")
    <style>
        .order-line-new {
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
                    <h3>Créer Commande</h3>
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
                            <form method="" action="" enctype="multipart/form-data">
                                <div class="client-infos">
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Fournisseur</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" value="{{ $order->provider->name }}" data-validate-length-range="6" data-validate-words="2" name="name" disabled />
                                        </div>
                                    </div>
                                </div>

                                <span class="section">Informations de Commande</span>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align">Titre de commande</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" value="{{ $order->title }}" data-validate-length-range="6" data-validate-words="2" name="title" disabled/>
                                        <hr>
                                    </div>
                                </div>

                                <div id="order-lines">
                                    @foreach($order->orderLines as $i => $ol)
                                        <div class="order-line {{$i != 0 ? 'order-line-new' : ''}}">
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Produit</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" value="{{$ol->product->label . ' - ' . $ol->product->ref}}" data-validate-length-range="6" data-validate-words="2" name="product" disabled/>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Quantité</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" value="{{ $ol->Qte }}" class="form-control" data-validate-length-range="6" data-validate-words="2" name="lines[{{$i}}][quantity]"  disabled/>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="ln_solid mt-1">
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3  pt-2">
                                            <a href="{{route('orders.index')}}" class="btn btn-secondary">Retour</a>
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



@endsection
