<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="directorio">
        <div id="searchResultsContainer" class="directorio">
            @foreach ($reuniones as $item)
                <div class="card_directorio">
                    <div class="user-card" data-search="{{ strtolower($item->name . ' ' . $item->date . ' ' . $item->time . ' ' . $item->duration . ' ' . $item->description) }}">
                        {{ $item->motivo_reunion }}<br>
                        {{ $item->participantes }}<br>
                        {{ $item->fecha_inicio }}<br>
                        {{ $item->fecha_fin }}<br>
                        <button><a href="#">Delete</a></button>
                    </div>
                </div>
            @endforeach
        </div>
</x-app-layout>