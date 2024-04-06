


@section('title', 'Réinitialisation Mot de passe')

@section("style_links")
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/animate.css/animate.min.css">
@endsection

@include("backOffice.layout.includes.header")

<body class="login">
<div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible" role="alert" id="myAlert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {{ session('status') }}
        </div>
    @endif


    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <h1>Réinitialiser Password</h1>
                    <p>{{ __("Mot de passe oublié ? Aucun problème. Indiquez simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra d'en choisir un nouveau.") }}</p>
                    <div>
                        @error('login')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="email" class="form-control" name="email" :value="old('email')" placeholder="Email" required />

                    </div>

                    <div class="clearfix"></div>

                    <div>
                        <a class="btn btn-default submit" href="#"><button class="btn">Evoyer</button> </a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">

                        <div>
                            <h1><i class="fa fa-car"></i> Auto Shop</h1>
                            <p>©2024 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<script>

    // Sélectionnez le bouton de fermeture
    var closeButton = document.querySelector('#myAlert .close');

    // Ajoutez un écouteur d'événements click au bouton de fermeture
    closeButton.addEventListener('click', function() {
        // Fermez l'alerte en masquant son élément parent
        document.querySelector('#myAlert').style.display = 'none';
    });
</script>
</body>

