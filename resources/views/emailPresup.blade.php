<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <form action="http://localhost:5678/webhook-test/fd1dd49a-a8a1-4c2b-882f-b9e0654a3f66" method="POST" enctype="multipart/form-data">
        <div class="presup mb-3">
            <label for="formFile" class="form-label">Selecciona tu archivo Excel:</label>
            @csrf
            <input class="" type="file" id="formFile" name="miArchivo">
             <input type="hidden" id="email" name="email" value="{{ Auth::user()->email }}" class="form-control">
            <label for="formFile2" class="form-label">Selecciona tu archivo PDF:</label>
            <input class="" type="file" id="formFile2" name="miPDF">
            
            <br><br>
            <label for="sistema">
                <input type="checkbox" name="sistema" id="sistema" value="Ya se encuentra en sistema" {{ old('sistema') ? 'checked' : '' }}>
                Ya se encuentra en sistema
            </label>
            <br><br>
            <label for="cambio">
                <input type="checkbox" name="cambio" id="cambio" value="Orden de cambio" {{ old('cambio') ? 'checked' : '' }}>
                Orden de cambio
            </label>
            <br><br>
            <label for="control">
                <input type="checkbox" name="control" id="control" value="Orden de cambio control" {{ old('control') ? 'checked' : '' }}>
                Orden de cambio control
            </label>
            <br><br>
            <label for="cerrar">
                <input type="checkbox" name="cerrar" id="cerrar" value="Cerrar Obra" {{ old('cerrar') ? 'checked' : '' }}>
                Cerrar Obra
            </label>
            <br><br>
            <label for="generacion">
                <input type="checkbox" name="generacion" id="generacion" value="Generación automática del presupuesto" {{ old('generacion') ? 'checked' : '' }}>
                Generación automática del presupuesto
            </label>
            <button class="boton" type="submit">Enviar correo</button>
        </div>
    </form>

    
</x-app-layout>