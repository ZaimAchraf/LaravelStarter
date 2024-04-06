@extends('layouts._home')

@section('title', 'Mobdie Kids - Contact')

@section('content')
    <div class="bg-cover bg-no-repeat bg-left-top"
         style="background-image: url('/images/banner.png');">
        <section class="px-6 py-10">
            <main class="max-w-6xl mx-auto mt-10 lg:mt-20 space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-tight mb-2 text-gray-100">
                        Contactez-nous
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold leading-tight mb-4 text-white">
                        Posez vos questions ou demandez des renseignements</p>
                </div>
            </main>
        </section>
    </div>

    <section class="bg-purple-600 mx-auto mt-5 lg:mt-4 space-y-6 px-6 py-8 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 px-4 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Nous contacter</h2>
                    <p class="mb-6">
                        Pour toute question ou demande d'informations sur les services de Mobdie Kids, veuillez remplir le formulaire ci-dessous. Nous vous répondrons dans les plus brefs délais.
                    </p>
                    <form action="{{ route('contact.submit') }}" method="POST" class="max-w-md mx-auto">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="font-bold mb-1">Nom</label>
                            <input type="text" id="name" name="name" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-600 text-black" required placeholder="Votre nom">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="font-bold mb-1">Adresse e-mail</label>
                            <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-600 text-black" required placeholder="Votre adresse e-mail">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="font-bold mb-1">Message</label>
                            <textarea id="message" name="message" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-600 text-black" rows="4" required placeholder="Votre message"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-yellow-600">
                                Envoyer
                            </button>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-2xl font-bold mb-4">Coordonnées</h2>
                    <p class="mb-4">
                        Vous pouvez également nous contacter directement par téléphone ou par e-mail :
                    </p>
                    <ul class="list-disc ml-8 mb-4">
                        <li>Téléphone : +212 669-367337</li>
                        <li>E-mail : contact@mobdie.com</li>
                    </ul>
                    <p class="mb-4">
                        N'hésitez pas à visiter notre centre éducatif pour discuter en personne avec notre équipe :
                    </p>
                    <p class="mb-2">
                        Mobdie Kids
                    </p>
                    <p class="mb-4">
                        Etage 1, Rue 6 N° 71, 2 Ave St Louis, Fes 30000
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
