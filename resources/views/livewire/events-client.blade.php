<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-col mb-8">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 w-full">
                        <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom de l'événement
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de début
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de fin
                            </th>
                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut d'inscription
                            </th>
                            <th scope="col" width="200" class="px-6 py-3 bg-gray-50">
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-hidden">
                        @foreach($events as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span style="word-wrap: break-word; white-space: pre-line;">
                                        {{ $event->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                    @if (str_word_count($event->description) > 5)
                                        <span style="word-wrap: break-word; white-space: pre-line;">
                                                {{ $event->description }}
                                            </span>
                                    @else
                                        {{ $event->description }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $event->start_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $event->end_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($event->clients->contains(auth()->user()))
                                        <span class="badge badge-success">{{__('Inscrit')}}</span>
                                    @else
                                        <span class="badge badge-danger">{{__('Non inscrit')}}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if(!$event->clients->contains(auth()->user()))
                                        <form wire:submit.prevent="registerClient({{ $event->id }})">
                                                <button type="submit" class="btn btn-sm btn-primary bg-indigo-400 hover:bg-indigo-600">
                                                    S'inscrire
                                                </button>
                                        </form>
                                    @else
                                        <form wire:submit.prevent="unregisterClient({{ $event->id }})">
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
