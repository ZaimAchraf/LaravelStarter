




@section('title', 'Changer Mot de passe')

@section("style_links")
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/animate.css/animate.min.css">
@endsection

@include("backOffice.layout.includes.header")

<body class="login">
<div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <h1>Modifier Mot de Passe</h1>

                    <div>
                        @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="email" class="form-control" name="email" :value="old('email', $request->email)" placeholder="Email" required />
                    </div>

                    <div>
                        @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="password" class="form-control" name="password"  placeholder="Email" required autocomplete="new-password" />
                    </div>

                    <div>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Email" required autocomplete="new-password" />
                    </div>

                    <div class="clearfix"></div>

                    <div>
                        <a class="btn btn-default submit" href="#"><button class="btn">Modifier</button> </a>
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
</body>

