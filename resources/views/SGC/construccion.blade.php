<x-app-layout>
    <x-slot name="header">
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        @endpush
    </x-slot>
    

    <div class="sgc_interno">
        <div class="background-oscuro">
            <div class="contenido-texto">
                <h2>Direccion de Construcción</h2>
                <p>Arq. Ricardo Solis Axotla</p>
            </div>
        </div>
        <div class="roles">
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Intendente</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Residente A</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Residente B</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Almacenista</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Auxiliar de Residente</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">Administrativo</a>
                </button>
            </div>
        </div>
    </div>
    </x-app-layout>