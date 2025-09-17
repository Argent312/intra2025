<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador SGC') }}
        </h2>
    </x-slot>
    <div class="adminsgc">
        <button class="send"><a href="{{ route('adminSGCAll') }}">Ver todos</a></button>
        <form action="/guardar-datos" method="POST" enctype="multipart/form-data" class="form-grid">
            @csrf

            <div class="form-left">
                <!-- Nombre -->
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <!-- Versión -->
                <label for="version">Versión:</label>
                <input type="text" id="version" name="version" required>

                <!-- Código -->
                <label for="codigo">Código:</label>
                <input type="text" id="codigo" name="codigo" required>

                <!-- Fecha de versión -->
                <label for="fecha_version">Fecha de versión:</label>
                <input type="date" id="fecha_version" name="fecha_version" required>

                <!-- Tipo -->
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="Procedimiento">Procedimiento</option>
                    <option value="Politica">Politica</option>
                    <option value="Formato">Formato</option>
                </select>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Piloto">Piloto</option>
                    <option value="Autorizado">Autorizado</option>
                </select>

                <!-- Documento PDF -->
                <label for="documento">Documento (PDF):</label>
                    <input type="file" id="documento" name="documento" accept="application/pdf" required>
                </div>

                <div class="form-right">
                    <div class="dropdown-checkbox">
                        <button type="button" class="dropdown-toggle" onclick="toggleDropdown('dir-list')">Direcciones</button>
                        <div id="dir-list" class="dropdown-content">
                            @foreach($directions as $direction)
                                <label>
                                    <input type="checkbox" name="direcciones[]" value="{{ $direction->id }}">
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
                                    <input type="checkbox" name="areas[]" value="{{ $area->id }}">
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
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                                        {{ $role->nombre_rol }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="send" type="submit">Guardar</button>
        </form>
    </div>

    <script>
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            el.style.display = el.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</x-app-layout>