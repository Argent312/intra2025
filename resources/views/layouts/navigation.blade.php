<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                    <x-nav-link :href="route('directorio')" :active="request()->routeIs('directorio')">
                        {{ __('Directorio') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sgc')" :active="request()->routeIs('sgc')">
                        {{ __('SGC') }}
                    </x-nav-link>
                
                    {{-- Dropdown en responsive --}}
                    <div x-data="{ open: false }" class="space-y-1 py-0 sm:py-0 space-x-2 relative z-50">
                        {{-- Botón para abrir el dropdown --}}
                            <button @click="open = !open"
                                class="w-full text-left px-2 py-4 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                                {{ __('Reuniones') }}
                            </button>

                        <div x-show="open" class="power space-y-1 pl-4" x-cloak>
                            <x-responsive-nav-link :href="route('sala')" target="_blank" :active="request()->routeIs('opcion1')">
                                {{ __('Sala de Juntas') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('comedor')" target="_blank" :active="request()->routeIs('opcion2')">
                                {{ __('Comedor') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('reuniones')" :active="request()->routeIs('opcion3')">
                                {{ __('Mis Reuniones') }}
                            </x-responsive-nav-link>
                        </div>
                    </div>


                    {{-- Dropdown en responsive --}}
                    <div x-data="{ open: false }" class="space-y-1 py-0 sm:py-0 space-x-2 relative z-50">
                        {{-- Botón para abrir el dropdown --}}
                            <button @click="open = !open"
                                class="w-full text-left px-2 py-4 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                                {{ __('Tableros BI') }}
                            </button>

                        <div x-show="open" class="power space-y-1 pl-4" x-cloak>
                            <x-responsive-nav-link href="https://app.powerbi.com/links/WbQQljIF9B?ctid=c9773dbb-728b-4a0c-bc43-bc181a28a289&pbi_source=linkShare" target="_blank" :active="request()->routeIs('opcion1')">
                                {{ __('Analitico de Vencimientos') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="https://app.powerbi.com/links/ClCv1pzoeE?ctid=c9773dbb-728b-4a0c-bc43-bc181a28a289&pbi_source=linkShare" target="_blank" :active="request()->routeIs('opcion2')">
                                {{ __('Presupuesto Administrativo') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiMzkwY2JkN2YtMzRkNy00ZDVkLWJhNzMtNzdhMzIxZWU3NTUwIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9" target="_blank" :active="request()->routeIs('opcion2')">
                                {{ __('Valor Proyecto') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiNTZkMzFjNzMtOWE0MC00N2FiLWFiNWYtNDAxNDgyZTQ0YWEyIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9 " target="_blank" :active="request()->routeIs('opcion2')">
                                {{ __('Cobranza 2025') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiZDA1OTIyOWItOTBkYy00YzJmLWEzMzUtMTU0OGJjYjZhMDQwIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9 " target="_blank" :active="request()->routeIs('opcion2')">
                                {{ __('Presupuesto MKT') }}
                            </x-responsive-nav-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('admin')">
                            {{ __('Admin') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Enlaces principales --}}
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Inicio') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('directorio')" :active="request()->routeIs('directorio')">
            {{ __('Directorio') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('sgc')" :active="request()->routeIs('sgc')">
            {{ __('SGC') }}
        </x-responsive-nav-link>

        {{-- Dropdown en responsive --}}
        <div x-data="{ open: false }" class="space-y-1">
        {{-- Dropdown en responsive --}}
            <div x-data="{ open: false }" class="space-y-1 py-2 sm:py-0 space-x-8 relative z-50">
                {{-- Botón para abrir el dropdown --}}
                    <button @click="open = !open"
                        class="w-full text-left px-4 py-4 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                        {{ __('Reuniones') }}
                    </button>

                    <div x-show="open" class="power space-y-1 pl-4" x-cloak>
                        <x-responsive-nav-link :href="route('sala')" :active="request()->routeIs('opcion1')">
                            {{ __('Sala de Juntas') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('comedor')" :active="request()->routeIs('opcion2')">
                            {{ __('Comedor') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
        {{-- Dropdown en responsive --}}
            <div x-data="{ open: false }" class="space-y-1 py-2 sm:py-0 space-x-8 relative z-50">
                {{-- Botón para abrir el dropdown --}}
                    <button @click="open = !open"
                        class="w-full text-left px-4 py-4 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                        {{ __('Tableros BI') }}
                    </button>

                <div x-show="open" class="power space-y-1 pl-4" x-cloak>
                    <x-responsive-nav-link href="https://app.powerbi.com/links/WbQQljIF9B?ctid=c9773dbb-728b-4a0c-bc43-bc181a28a289&pbi_source=linkShare" target="_blank" :active="request()->routeIs('opcion1')">
                        {{ __('Analitico de Vencimientos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="https://app.powerbi.com/links/ClCv1pzoeE?ctid=c9773dbb-728b-4a0c-bc43-bc181a28a289&pbi_source=linkShare" target="_blank" :active="request()->routeIs('opcion2')">
                        {{ __('Presupuesto Administrativo') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiMzkwY2JkN2YtMzRkNy00ZDVkLWJhNzMtNzdhMzIxZWU3NTUwIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9" target="_blank" :active="request()->routeIs('opcion2')">
                        {{ __('Valor Proyecto') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiNTZkMzFjNzMtOWE0MC00N2FiLWFiNWYtNDAxNDgyZTQ0YWEyIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9 " target="_blank" :active="request()->routeIs('opcion2')">
                        {{ __('Cobranza 2025') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="https://app.powerbi.com/view?r=eyJrIjoiZDA1OTIyOWItOTBkYy00YzJmLWEzMzUtMTU0OGJjYjZhMDQwIiwidCI6ImM5NzczZGJiLTcyOGItNGEwYy1iYzQzLWJjMTgxYTI4YTI4OSJ9 " target="_blank" :active="request()->routeIs('opcion2')">
                        {{ __('Presupuesto MKT') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>
    </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>


                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
