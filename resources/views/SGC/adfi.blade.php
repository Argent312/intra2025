<x-app-layout>
    <x-slot name="header">
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        @endpush
    </x-slot>
    

    <div class="sgc_interno">
        <div class="background-adfi">
            <div class="contenido-texto">
                <h2>Administracion y Finanzas</h2>
                <p>C. Jaqueline Palmeros</p>
            </div>
        </div>
        <div class="roles">
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">GERENTE</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">TESORERIA</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">AUXILIAR</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">CONTABILIDAD</a>
                </button>
            </div>
        </div>
    </div>
    </x-app-layout>