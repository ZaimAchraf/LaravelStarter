@section('title', 'Se connecter')

@section("style_links")
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="{{asset("adminPanel")}}/vendors/animate.css/animate.min.css">
@endsection

@include("backOffice.layout.includes.header")

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1>Login Form</h1>
                    <div>
                        @error('login')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="email" class="form-control" name="login" placeholder="Email" required />

                    </div>
                    <div>
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="password" class="form-control" name="password" placeholder="Password" required />

                    </div>
                    <div style="margin-top: -15px">
                        @if (Route::has('password.request'))
                            <a class="reset_pass"
                               href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        @endif
                    </div>

                    <div class="clearfix"></div>

                    <div>
                        <a class="btn btn-default submit" href="#"><button class="btn">Log in</button> </a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">New to site?
                            <a href="#signup" class="to_register"> Create Account </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-car"></i> AutoBody</h1>
                            <p>©2024 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>

        <div id="register" class="animate form registration_form">
            <section class="login_content">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h1>Create Account</h1>
                    <div>
                        <input type="text" name="name" class="form-control" placeholder="Nom Complet" required="" />
                    </div>
                    <div>
                        <input type="email" name="email" class="form-control" placeholder="Email" required="" />
                    </div>
                    <div>
                        @error('username')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required="" />
                    </div>

                    <div>
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="" />
                    </div>
                    <div>
                        @error('password_confirmation')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le Mot de passe" required="" />
                    </div>
                    <div>
                        <select name="role" id="role" class="form-control" required>
                            <option disabled selected value="">Select Role</option>
                            <option value="4">Employee</option>
                            <option value="3">Client</option>
                            <option value="2">Admin</option>
                            <option value="1">Super Admin</option>
                        </select>
                    </div>

                    <div>
                        <a class="btn btn-default submit" href="#"><button class="btn">Souscrire</button> </a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Already a member ?
                            <a href="#signin" class="to_register"> Log in </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-car"></i> AutoBody</h1>
                            <p>©2024 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>



{{--<form method="POST" action="{{ route('register') }}">--}}
{{--    @csrf--}}

{{--    <div>--}}
{{--        <x-label for="name" value="{{ __('Name') }}" />--}}
{{--        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />--}}
{{--    </div>--}}

{{--    <div class="mt-4">--}}
{{--        <x-label for="email" value="{{ __('Email') }}" />--}}
{{--        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />--}}
{{--    </div>--}}

{{--    <div class="mt-4">--}}
{{--        <x-label for="username" value="{{ __('Username') }}" />--}}
{{--        <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"--}}
{{--                 required />--}}
{{--    </div>--}}

{{--    <div class="mt-4">--}}
{{--        <x-label for="role" value="{{ __('Role') }}" />--}}
{{--        <select name="role" id="role" class="block mt-1 w-full" required>--}}
{{--            <option disabled selected value="">Select Role</option>--}}
{{--            <option value="3">Client</option>--}}
{{--            <option value="2">Admin</option>--}}
{{--            <option value="1">Super Admin</option>--}}
{{--        </select>--}}
{{--    </div>--}}

{{--    <div class="mt-4">--}}
{{--        <x-label for="password" value="{{ __('Password') }}" />--}}
{{--        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />--}}
{{--    </div>--}}

{{--    <div class="mt-4">--}}
{{--        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />--}}
{{--        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />--}}
{{--    </div>--}}

{{--    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())--}}
{{--        <div class="mt-4">--}}
{{--            <x-label for="terms">--}}
{{--                <div class="flex items-center">--}}
{{--                    <x-checkbox name="terms" id="terms" required />--}}

{{--                    <div class="ml-2">--}}
{{--                        {!! __('I agree to the :terms_of_service and :privacy_policy', [--}}
{{--                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',--}}
{{--                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',--}}
{{--                        ]) !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </x-label>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <div class="flex items-center justify-end mt-4">--}}
{{--        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">--}}
{{--            {{ __('Already registered?') }}--}}
{{--        </a>--}}

{{--        <x-button class="ml-4">--}}
{{--            {{ __('Register') }}--}}
{{--        </x-button>--}}
{{--    </div>--}}
{{--</form>--}}
