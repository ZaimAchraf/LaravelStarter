<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-col mb-8">
        <div class="-my-2 sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 w-full">
                        <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom de l'offre
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de Fin
                            </th>
                            <th scope="col" width="200" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Réduction
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cours inclus dans l'offre
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut d'inscription
                            </th>
                            <th scope="col" width="200" class="px-6 py-3 bg-gray-50">
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-hidden">
                        @foreach($offers as $offer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $offer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                    @if (str_word_count($offer->description) > 5)
                                        <span style="word-wrap: break-word; white-space: pre-line;">
                                                {{ $offer->description }}
                                            </span>
                                    @else
                                        {{ $offer->description }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $offer->end_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $offer->discount }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @foreach($offer->courses as $course)
                                            <div class="badge badge-primary mb-2">{{ $course->title }}</div><br>
                                        @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($offer->clients->contains(auth()->user()))
                                        <span class="badge badge-success">{{__('Inscrit')}}</span>
                                    @else
                                        <span class="badge badge-danger">{{__('Non inscrit')}}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if(!$offer->clients->contains(auth()->user()))
                                        <form wire:submit.proffer="registerClient({{ $offer->id }})">
                                            <button type="submit" class="btn btn-sm btn-primary bg-indigo-400 hover:bg-indigo-600">
                                                S'inscrire
                                            </button>
                                        </form>
                                    @else
                                        <form wire:submit.proffer="unregisterClient({{ $offer->id }})">
                                            <button type="submit" class="btn btn-sm btn-danger bg-indigo-400 hover:bg-indigo-600">
                                                Se désinscrire
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
