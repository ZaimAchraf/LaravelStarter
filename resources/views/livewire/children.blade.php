@if ($editing)
    <div class="flex flex-col mb-8">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                        Modifier
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateChild({{ $child['id']}})">
                            <div class="form-group row">
                                <div class="col">
                                    <label for="name">
                                        Nom et Prénom de l'enfant
                                    </label>
                                    <input type="text" wire:model="name" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="col">
                                    <label for="birthdate">
                                        Date de naissance
                                    </label>
                                    <input type="date" wire:model="birthdate" name="birthdate" id="birthdate" class="form-control" required>
                                </div>

                                <div class="col">
                                    <label for="courses">Cours inscrites</label>
                                    <select class="form-control" multiple id="courses" wire:model="selectedCourses">
                                        @foreach($allCourses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary bg-purple-500 hover:bg-purple-700">Modifier</button>
                                <button type="button" class="btn btn-link" wire:click.prevent="cancelEdit">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card mb-8">
            <table class="table">
                <thead>
                <tr>
                    <th>Nom et Prénom de l'enfant</th>
                    <th>Date de naissance</th>
                    <th>Cours inscrites</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($client->children as $child)
                    <tr>
                        <td>{{ $child->name }}</td>
                        <td>{{ $child->birthdate }}</td>
                        <td>
                            @foreach($child->courses as $course)
                                <div class="badge badge-primary mb-2">{{ $course->title }}</div><br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center">
                                <a href="#" wire:click.prevent="editChild({{ $child->id }})">
                                    <i class="fas fa-edit bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded mr-2"></i>
                                </a>
                                <a href="#" wire:click.prevent="deleteChild({{ $child->id }})">
                                    <i class="fas fa-trash-alt bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                        + Ajouter un nouveau enfant
                    </div>
                        <div class="card-body">
                            <form action="{{ route('children.store', $client->id) }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="name">Nom et Prénom de l'enfant</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col">
                                        <label for="birthdate">Date de naissance</label>
                                        <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                                    </div>
                                    <div class="col">
                                        <label for="courses">Cours inscrites</label>
                                        <select class="form-control" multiple id="courses" name="courses[]">
                                            @foreach($allCourses as $course)
                                                <option value="{{ $course->id }}" {{ in_array($course->id, old('courses') ?? []) ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary bg-purple-600 hover:bg-purple-700">Ajouter</button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endif
