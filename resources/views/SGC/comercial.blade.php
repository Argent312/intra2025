<x-app-layout>
    <x-slot name="header">
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        @endpush
    </x-slot>
    

    <div class="sgc_interno">
        <div class="background-comercial">
            <div class="contenido-texto">
                <h2>Direccion Comercial</h2>
                <p>C. Oscar Landa</p>
            </div>
        </div>
        <div class="roles">
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">GERENTE DE ZONA</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">COORDINADOR</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">ASESOR</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">CONTACT CENTER</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">MARKETING</a>
                </button>
            </div>
            <div class="roles_cards">
                <button class="user_card">
                    <a href="{{ route('sgc.construccion') }}">GERENTE</a>
                </button>
            </div>
        </div>
    </div>
    </x-app-layout>