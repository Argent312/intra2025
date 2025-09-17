<x-app-layout>
    <x-slot name="header">
        <style>
            
.edit .form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  padding: 2rem;
  background-color: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.edit .form-left,
.edit .form-right {
  flex: 1 1 45%;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.edit label {
  font-weight: 600;
  margin-bottom: 0.3rem;
  color: #333;
}

.edit input[type="text"],
.edit input[type="date"],
.edit input[type="file"],
.edit select,
.edit textarea {
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  width: 100%;
  box-sizing: border-box;
}

.edit select {
  background-color: #fff;
}

.edit .send {
  background-color: #007bff;
  color: white;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 2rem;
}

.edit .send:hover {
  background-color: #0056b3;
}

.edit .dropdown-checkbox {
  margin-bottom: 1rem;
}

.edit .dropdown-toggle {
  background-color: #e0e0e0;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  width: 100%;
  text-align: left;
}

.edit .dropdown-content {
  display: none;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 0.5rem;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fff;
  max-height: 200px;
  overflow-y: auto;
}

.edit .dropdown-content label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.edit .dropdown-content input[type="checkbox"] {
  transform: scale(1.2);
}

.edit button.dropdown-toggle.active + .dropdown-content {
  display: flex;
}
        </style>

    </x-slot>

    <div class="edit">
<form action="{{ route('procesos.update', $proceso->id) }}" method="POST" enctype="multipart/form-data" class="form-grid">
    @csrf
    @method('PUT')

    <div class="form-left">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $proceso->nombre_proceso) }}" required>

        <label for="version">Versión:</label>
        <input type="text" id="version" name="version" value="{{ old('version', $proceso->version) }}" required>

        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $proceso->codigo) }}" required>

        <label for="fecha_version">Fecha de versión:</label>
        <input type="date" id="fecha_version" name="fecha_version" value="{{ old('fecha_version', $proceso->fecha_version) }}" required>

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <option value="Procedimiento" {{ $proceso->tipo == 'Procedimiento' ? 'selected' : '' }}>Procedimiento</option>
            <option value="Politica" {{ $proceso->tipo == 'Politica' ? 'selected' : '' }}>Política</option>
            <option value="Formato" {{ $proceso->tipo == 'Formato' ? 'selected' : '' }}>Formato</option>
        </select>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="Piloto" {{ $proceso->estado == 'Piloto' ? 'selected' : '' }}>Piloto</option>
            <option value="Autorizado" {{ $proceso->estado == 'Autorizado' ? 'selected' : '' }}>Autorizado</option>
        </select>

        <label for="documento">Documento (PDF):</label>
        <input type="file" id="documento" name="documento" accept="application/pdf">
        @if($proceso->documento)
            <p>Documento actual: <a href="{{ asset('storage/' . $proceso->documento) }}" target="_blank">Ver PDF</a></p>
        @endif
    </div>

    <div class="form-right">
        <div class="dropdown-checkbox">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dir-list')">Direcciones</button>
            <div id="dir-list" class="dropdown-content">
                @foreach($directions as $direction)
                    <label>
                        <input type="checkbox" name="direcciones[]" value="{{ $direction->id }}"
                            {{ $proceso->directions->contains($direction->id) ? 'checked' : '' }}>
                        {{ $direction->nombre_direccion }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="dropdown-checkbox">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('area-list')">Áreas</button>
            <div id="area-list" class="dropdown-content">
                @foreach($areas as $area)
                    <label>
                        <input type="checkbox" name="areas[]" value="{{ $area->id }}"
                            {{ $proceso->areas->contains($area->id) ? 'checked' : '' }}>
                        {{ $area->nombre_area }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="dropdown-checkbox">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown('rol-list')">Puestos</button>
            <div id="rol-list" class="dropdown-content">
                @foreach($roles as $role)
                    <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ $proceso->roles->contains($role->id) ? 'checked' : '' }}>
                        {{ $role->nombre_rol }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <button class="send" type="submit">Guardar cambios</button>
</form>

</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
}
</script>

</x-app-layout>