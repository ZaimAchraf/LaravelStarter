@if ($editing)
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                        Modifier
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateEvent({{ $event['id']}})">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="name">Nom de l'événement:</label>
                                    <input wire:model="name" type="text" id="name" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="description">Description:</label>
                                    <textarea wire:model="description" id="description" class="form-input w-full px-3 py-2 border rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description','') }}</textarea>
                                    @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="start_date">Date de début:</label>
                                    <input wire:model="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col">
                                    <label for="end_date">Date de fin:</label>
                                    <input wire:model="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary uppercase bg-purple-600 hover:bg-purple-700">Modifier</button>
                                <button type="button" class="btn btn-link" wire:click.prevent="cancelEdit">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex justify-center">
                                        <a href="#" wire:click.prevent="editEvent({{ $event->id }})">
                                            <i class="fas fa-edit bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded mr-2"></i>
                                        </a>
                                        <a href="#" wire:click.prevent="deleteEvent({{ $event->id }})">
                                            <i class="fas fa-trash-alt bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                        <button wire:click="toggleAddForm">+ Ajouter un nouveau événement</button>
                    </div>
                    @if($showAddForm)
                        <div class="card-body">
                            <form wire:submit.prevent="create">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="name">Nom de l'événement:</label>
                                        <input wire:model="name" type="text" id="name" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        <label for="description">Description:</label>
                                        <textarea wire:model="description" id="description" class="form-input w-full px-3 py-2 border rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description','') }}</textarea>
                                        @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="start_date">Date de début:</label>
                                        <input wire:model="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Date de fin:</label>
                                        <input wire:model="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary bg-purple-600 hover:bg-purple-700">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
