<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="directorio">
        <div id="searchResultsContainer" class="directorio">
            @foreach ($tableros as $item)
                <div class="card_directorio">
                    <div class="user-card" data-search="{{ strtolower($item->nombre) }}">
                        {{ $item->nombre }}<br>
                        <a href="{{ $item->url }}" target="_blank">Ver</a>
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
</x-app-layout>