<x-app-layout>
    <x-slot name="header">
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        @endpush
    </x-slot>
    

    <div class="sgc_interno">
        <div class="background-rh">
            <div class="contenido-texto">
                <h2>Recursos Humanos</h2>
                <p>Lic. Maira Castilla</p>
            </div>
        </div>
        <div class="roles">
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">POLITICAS</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">PROCEDIMIENTOS</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">RESPONSIVAS</a>
                </button>
            </div>
        </div>
    </div>
    </x-app-layout>