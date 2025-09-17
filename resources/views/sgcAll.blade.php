<x-app-layout>
    <x-slot name="header">
        
    </x-slot>
    <div class="adminsgc">
        <button class="send"><a href="/AdminSGC">Ir a Administrador SGC</a></button>
        <div class="allsgc">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Versión</th>
                        <th>Código</th>
                        <th>Fecha de versión</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procesos as $item)
                    <tr>
                        <td>{{ $item->nombre_proceso }}</td>
                        <td>{{ $item->version }}</td>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->fecha_version }}</td>
                        <td>{{ $item->tipo }}</td>
                        <td>{{ $item->estado }}</td>
                        <td><a href="{{ url($item->ruta) }}" target="_blank">Ver Documento</a></td>
                        <td><a href="{{ route('editarProceso', $item->id) }}">Edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</x-app-layout>