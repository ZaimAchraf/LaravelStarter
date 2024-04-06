@extends('layouts._home')

@section('title', 'Mobdie Kids - Galerie photos')

@section('content')

    <div class="bg-cover bg-no-repeat bg-left-top"
         style="background-image: url('/images/banner.png');">
        <section class="px-6 py-10">
            <main class="max-w-6xl mx-auto mt-10 lg:mt-20 space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-tight mb-2 text-gray-100">
                        Galerie photos
                    </h1>
                </div>
            </main>
        </section>
    </div>

    <section class="bg-purple-600 mx-auto mt-5 lg:mt-4 space-y-6 px-6 py-8">
        <div class="px-6 py-8 flex justify-center">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <img src="/images/image1.jpg" alt="Galerie Image 1" width="600" height="400"
                                 class="rounded-lg mx-auto">
                        </li>
                        <li class="splide__slide">
                            <img src="https://via.placeholder.com/600x400" alt="Galerie Image 2"
                                 class="rounded-lg mx-auto">
                        </li>
                        <li class="splide__slide">
                            <img src="https://via.placeholder.com/600x400" alt="Galerie Image 3"
                                 class="rounded-lg mx-auto">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    @parent
    <script>
        new Splide('.splide', {
            type: 'loop',
            perPage: 1,
            autoplay: true,
        }).mount();
    </script>
@endsection
