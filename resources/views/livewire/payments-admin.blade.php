<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input wire:model.debounce.300ms="search" type="text" class="form-control" id="search" placeholder="&nbsp;Rechercher...">
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <select wire:model="statusFilter" id="statusFilter" class="form-control">
                <option value="">-- Tous les statuts --</option>
                <option value="paid">Payé</option>
                <option value="unpaid">Non-payé</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col mb-8">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 w-full">
            <thead>
            <tr>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Client
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Montant
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Statut
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date d'échéance
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date de paiement
                </th>
                <th scope="col" width="200" class="px-6 py-3 bg-gray-50">
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($payments as $payment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->amount }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if ($payment->status === 'paid')
                            <span class="badge badge-success">{{__('payé')}}</span>
                        @else
                            <span class="badge badge-danger">{{__('non-payé')}}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->due_date->format('Y-d-m') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->payment_date ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if ($payment->status === 'unpaid')
                            <button wire:click="markAsPaid({{ $payment->id }})" class="btn btn-sm btn-primary bg-indigo-400 hover:bg-indigo-600">Marquer comme payé</button>
                        @else
                            <button wire:click="markAsUnpaid({{ $payment->id }})" class="btn btn-sm btn-primary bg-indigo-400 hover:bg-indigo-600">Marquer comme non-payé</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucun paiement trouvé.</td>
                </tr>
            @endforelse
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
                        <button wire:click="toggleAddForm">+ Ajouter un nouveau paiement</button>
                    </div>
                    @if($showAddForm)
                        <div class="card-body">
                            <form wire:submit.prevent="create">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="user_id">Client:</label>
                                        <select wire:model="user_id" class="form-control @error('user_id') is-invalid @enderror" id="user_id">
                                            <option value="">-- Sélectionnez un client --</option>
                                            @foreach($users->where('role_id', 3) as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label for="amount">Montant:</label>
                                        <input wire:model="amount" type="text" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Montant">
                                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label for="due_date">Date d'échéance:</label>
                                        <input wire:model="due_date" type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date">
                                        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary bg-purple-600 hover:bg-purple-700">Ajouter</button>
                                    </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
