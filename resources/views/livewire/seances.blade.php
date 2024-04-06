<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Séances</label>

    <div class="mt-2">
        <div class="flex flex-col space-y-4">
            @foreach($seances as $seance)
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ \App\Models\Course::getDayName($seance->day) }}</div>
                        <div class="text-sm text-gray-500">{{ $seance->start_time->format('H:i') }} - {{ $seance->end_time->format('H:i') }}</div>
                    </div>
                    <div>
                        <button wire:click="deleteSeance({{ $seance->id }})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <label for="day" class="block text-sm font-medium text-gray-700 mb-2">Jour</label>
        <select wire:model="day" id="day" name="day" class="form-select w-full px-3 py-2 border rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('day') border-red-500 @enderror" default="1">
            <option value="">Sélectionner un jour</option>
            @foreach(['1' => 'Lundi', '2' => 'Mardi', '3' => 'Mercredi', '4' => 'Jeudi', '5' => 'Vendredi', '6' => 'Samedi', '7' => 'Dimanche'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        @error('day')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-6">
        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de début</label>
        <input wire:model="start_time" type="time" id="start_time" name="start_time" class="form-input w-full px-3 py-2 border rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('start_time') border-red-500 @enderror">
        @error('start_time')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-6">
        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de fin</label>
        <input wire:model="end_time" type="time" id="end_time" name="end_time" class="form-input w-full px-3 py-2 border rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('end_time') border-red-500 @enderror">
        @error('end_time')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-6">
        <button wire:click="addSeance" wire:loading.remove
                class="btn btn-primary bg-purple-600 hover:bg-purple-700">
                Ajouter séance
        </button>
    </div>
</div>
