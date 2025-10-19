<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Ingreso de Pacientes</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Ingresa tu código de paciente para acceder a tus actividades.</p>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('patient.login') }}">
            @csrf

            <div>
                <x-label for="codigo" value="{{ __('Código de paciente') }}" />
                <x-input id="codigo" class="block mt-1 w-full" type="text" name="codigo" :value="old('codigo')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button>
                    {{ __('Ingresar') }}
                </x-button>
            </div>
        </form>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Admin o terapeuta</a>
            @if (Route::has('register'))
                <x-button type="button" onclick="location.href='{{ route('register') }}'" class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                    {{ __('Registrarse') }}
                </x-button>
            @endif
        </div>
    </x-authentication-card>
</x-guest-layout>
