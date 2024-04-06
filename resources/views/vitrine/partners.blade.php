@extends('layouts._home')

@section('title', 'Mobdie Kids - Nos partenaires')

@section('content')
    <div class="bg-cover bg-no-repeat bg-left-top"
         style="background-image: url('/images/banner.png');">
        <section class="px-6 py-10">
            <main class="max-w-6xl mx-auto mt-10 lg:mt-20 space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-tight mb-2 text-gray-100">
                        Nos partenaires
                    </h1>
                </div>
            </main>
        </section>
    </div>

    <section class="bg-purple-600 mx-auto mt-5 lg:mt-4 space-y-6 px-6 py-8 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">Écoles partenaires</h2>
                <div class="flex justify-center items-center mb-6">
                    <img src="/images/logo-abc.png" alt="Logo École ABC" class="h-12 mx-4">
                    <img src="/images/logo-xyz.png" alt="Logo École XYZ" class="h-12 mx-4">
                    <img src="/images/logo-def.png" alt="Logo École DEF" class="h-12 mx-4">
                </div>
                <h2 class="text-2xl font-bold mb-4">Centres de formation</h2>
                <div class="flex justify-center items-center mb-6">
                    <img src="/images/logo-centre-123.png" alt="Logo Centre de formation 123" class="h-12 mx-4">
                    <img src="/images/logo-centre-abc.png" alt="Logo Centre de formation ABC" class="h-12 mx-4">
                    <img src="/images/logo-centre-xyz.png" alt="Logo Centre de formation XYZ" class="h-12 mx-4">
                </div>
            </div>
        </div>
    </section>
@endsection
