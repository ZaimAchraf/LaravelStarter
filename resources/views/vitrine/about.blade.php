@extends('layouts._home')

@section('title', 'Mobdie Kids - À propos')

@section('content')
    <div class="bg-cover bg-no-repeat bg-left-top"
         style="background-image: url('/images/banner.png');">
        <section class="px-6 py-10">
            <main class="max-w-6xl mx-auto mt-10 lg:mt-20 space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-tight mb-2 text-gray-100">
                        À propos
                    </h1>
                </div>
            </main>
        </section>
    </div>

    <section class="bg-purple-600 mx-auto mt-5 lg:mt-4 space-y-6 px-6 py-8 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">Notre mission</h2>
                <p class="mb-6">
                    Chez Mobdie Kids, notre mission est d'offrir un environnement éducatif et sécurisé où chaque enfant peut développer ses compétences, sa créativité et sa confiance en soi. Nous croyons en une approche holistique de l'apprentissage qui stimule la curiosité naturelle des enfants et favorise leur épanouissement global.
                </p>
                <h2 class="text-2xl font-bold mb-4">Notre histoire</h2>
                <p class="mb-6">
                    Depuis notre création en 2010, Mobdie Kids s'est engagé à fournir des services éducatifs de qualité aux enfants. Nous avons grandi en tant qu'organisation en établissant des partenariats solides avec les écoles et les familles, et en développant un programme d'apprentissage complet qui répond aux besoins uniques de chaque enfant.
                </p>
                <h2 class="text-2xl font-bold mb-4">Notre équipe pédagogique</h2>
                <p class="mb-6">
                    Notre équipe pédagogique est composée d'enseignants dévoués et passionnés qui possèdent une vaste expérience dans l'éducation des enfants. Ils sont formés pour créer un environnement d'apprentissage positif, interactif et stimulant où chaque enfant peut s'épanouir. Nous croyons en une approche individualisée qui reconnaît les besoins uniques de chaque enfant et favorise leur développement global.
                </p>
            </div>
        </div>
    </section>
@endsection
