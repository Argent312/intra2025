<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="container">
        <h2>Editar Reunión</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reuniones.sala.update', $reunion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="motivo_reunion" class="form-label">Motivo de la Reunión</label>
                <input type="text" class="form-control" id="motivo_reunion" name="motivo_reunion" value="{{ $reunion->motivo_reunion }}" required>
            </div>

            <div class="mb-3">
                <label for="participantes" class="form-label">Participantes</label>
                <textarea class="form-control" id="participantes" name="participantes" rows="3" required>{{ $reunion->participantes }}</textarea>
            </div>  
            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ date('Y-m-d\TH:i', strtotime($reunion->fecha_inicio)) }}" required>
            </div>
            <div class="mb-3">
                <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
                <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ date('Y-m-d\TH:i', strtotime($reunion->fecha_fin)) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Reunión</button>
            <a href="{{ route('reuniones') }}" class="btn btn-secondary">Cancelar
            </a>
        </form>
    </div>
</x-app-layout>
