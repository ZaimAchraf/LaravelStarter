@extends('layouts._home')

@section('title', 'Mobdie Kids - Reviews')

@section('content')
    <div class="bg-cover bg-no-repeat bg-left-top"
         style="background-image: url('/images/banner.png');">
        <section class="px-6 py-10">
            <main class="max-w-6xl mx-auto mt-10 lg:mt-20 space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-tight mb-2 text-gray-100">
                        Reviews
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold leading-tight mb-4 text-white">
                        Commentaires et évaluations sur nos services</p>
                </div>
            </main>
        </section>
    </div>

    <section class="bg-white py-12">
        <div class="max-w-6xl mx-auto">
            <div>
                <div class="space-y-4">
                    @foreach ($reviews as $review)
                        <div class="bg-gray-200 px-4 py-6 rounded-lg">
                            <p class="mb-2">{{ $review->comment }}</p>
                            <div class="flex items-center">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <svg class="w-5 h-5 fill-current text-yellow-500" viewBox="0 0 20 20">
                                                <path d="M10 12.59l-4.95 3.68 1.9-5.84L2.38 7.93l5.93-.05L10 2.5l2.69 5.38 5.93.05-4.57 3.5 1.9 5.84L10 12.59z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 fill-current text-gray-400" viewBox="0 0 20 20">
                                                <path d="M10 12.59l-4.95 3.68 1.9-5.84L2.38 7.93l5.93-.05L10 2.5l2.69 5.38 5.93.05-4.57 3.5 1.9 5.84L10 12.59z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-gray-600 ml-2">{{ $review->user->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center mt-12">
                <h2 class="text-2xl font-bold mb-4">Laissez votre avis</h2>
                @auth
                    <form action="{{ route('reviews.store') }}" method="POST" class="max-w-lg mx-auto">
                        @csrf
                        <textarea name="comment"
                                  id="comment"
                                  rows="4"
                                  class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-600 text-black"
                                  placeholder="Écrivez votre commentaire"></textarea>
                        <input type="number"
                               name="rating" id="rating" min="1" max="5"
                               class="w-full rounded-lg border border-gray-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                               placeholder="Note (1-5)">
                        <button type="submit" class="transition-colors duration-300 bg-purple-600 hover:bg-purple-800 rounded-full py-2 px-6 text-sm font-semibold text-white uppercase mt-4">
                            Soumettre
                        </button>
                    </form>
                @else
                    <p class="mb-6">
                        Connectez-vous pour laisser un commentaire et une évaluation sur nos services.
                    </p>
                    <a href="{{ route('login') }}" class="transition-colors duration-300 bg-purple-600 hover:bg-purple-800 rounded-full py-2 px-6 text-sm font-semibold text-white uppercase">
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </section>
@endsection
