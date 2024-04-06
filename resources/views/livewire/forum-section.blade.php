<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="alert alert-info">
        <strong>Bienvenue sur notre Forum Mobdie !</strong> Ce forum est un espace dédié aux parents où vous pouvez échanger des conseils,
        poser des questions et discuter des activités parascolaires pour vos enfants.
        Nous encourageons une communauté bienveillante et respectueuse,
        où chacun peut partager son expérience et apporter son soutien.
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input wire:model.debounce.300ms="search" type="text" class="form-control" id="search" placeholder="&nbsp;Rechercher...">
            </div>
        </div>
    </div>
    <!-- Display forum posts -->
    @foreach ($posts as $post)
        <a href="{{ route('forum.post', ['id' => $post->id]) }}" class="card mb-3 hover-open">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-2xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h2>
                    <p class="card-text text-gray-600 mb-2">{{ $post->content }}</p>
                    <p class="card-text text-gray-400 text-sm">Publié par: {{ $post->user->name }}</p>
                    <div class="flex items-center mt-2">
                        <div class="ml-auto flex items-center">
                            <i class="fas fa-comment text-gray-400 mr-1"></i>
                            <p class="text-gray-400 text-sm">{{ $post->comments->count() }}</p>
                            @if ($post->user_id === auth()->id())
                                <form action="{{ route('forum.delete', ['id' => $post->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

    <!-- Create a new post form -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="px-6 py-3 bg-purple-300 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                        <button wire:click="toggleAddForm">+ Créer une nouvelle publication</button>
                    </div>
                    @if($showAddForm)
                        <div class="card-body">
                            <form wire:submit.prevent="createPost">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre :</label>
                                    <input type="text" wire:model="title" id="title" class="form-control">
                                    @error('title') <span class="error">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Contenu :</label>
                                    <textarea wire:model="content" id="content" class="form-control"></textarea>
                                    @error('content') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <button class="btn btn-primary bg-purple-600 hover:bg-purple-700">
                                    Soumettre
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
