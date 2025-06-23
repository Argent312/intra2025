<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="buscador">
        <label for="search">Buscar:</label>
        <div class="mb-3">
            <input type="text" class="form-control" id="inputBusqueda" placeholder="Nombre o correo ....">
        </div>
    </div>

    <div class="directorio">
        <div id="searchResultsContainer" class="directorio">
            @foreach ($users as $item)
                <div class="card_directorio">
                    <div class="user-card" data-search="{{ strtolower($item->name . ' ' . $item->last_name . ' ' . $item->email . ' ' . $item->ext . ' ' . $item->area) }}">
                        {{ $item->name }} {{ $item->last_name }}<br>
                        {{ $item->email }}<br>
                        {{ $item->ext }}<br>
                        {{ $item->area }}<br>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
</x-app-layout>
