<x-event-layout>

    <x-slot name="title">
        {{ __('Detalles del Terapeuta') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Información del Terapeuta</h2>
                        <a href="{{ route('therapists.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Volver
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Foto y acciones -->
                        <div class="md:col-span-1">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg p-6 text-white text-center">
                                <div class="mx-auto h-32 w-32 bg-white rounded-full flex items-center justify-center mb-4">
                                    <span class="text-indigo-600 font-bold text-4xl">
                                        {{ strtoupper(substr($therapist->nombres, 0, 1) . substr($therapist->apellidos, 0, 1)) }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold mb-1">{{ $therapist->nombres }} {{ $therapist->apellidos }}</h3>
                                <p class="text-indigo-100 text-sm mb-4">Terapeuta</p>

                                @can('therapist-edit')
                                    <a href="{{ route('therapists.edit', $therapist->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-50 transition w-full justify-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Editar
                                    </a>
                                @endcan

                                @can('therapist-delete')
                                    <form action="{{ route('therapists.destroy', $therapist->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este terapeuta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-700 transition w-full justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <!-- Información detallada -->
                        <div class="md:col-span-2">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Datos Personales
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->nombres }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Apellidos</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->apellidos }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">DNI</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->dni }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fecha de Nacimiento</label>
                                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($therapist->nacimiento)->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($therapist->nacimiento)->age }} años</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sexo</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->sexo === 'M' ? 'Masculino' : 'Femenino' }}</p>
                                    </div>
                                </div>

                                <hr class="my-6 border-gray-200">

                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                    Información de Contacto
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->email }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Teléfono</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->telefono }}</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Dirección</label>
                                        <p class="text-gray-900 font-medium">{{ $therapist->direccion }}</p>
                                    </div>
                                </div>

                                @if($therapist->user)
                                    <hr class="my-6 border-gray-200">

                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                        </svg>
                                        Cuenta de Usuario
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Usuario</label>
                                            <p class="text-gray-900 font-medium">{{ $therapist->user->name }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email de acceso</label>
                                            <p class="text-gray-900 font-medium">{{ $therapist->user->email }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Rol</label>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $therapist->user->role ?? 'therapist' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sección de pacientes asignados -->
                    @can('patient-list')
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                Pacientes Asignados ({{ $therapist->patients->count() }})
                            </h4>

                            @if($therapist->patients->count() > 0)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($therapist->patients as $patient)
                                            <a href="{{ route('patients.show', $patient->id) }}" 
                                                class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transition">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                        <span class="text-green-600 font-semibold text-sm">
                                                            {{ strtoupper(substr($patient->nombres, 0, 1) . substr($patient->apellidos, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $patient->nombres }} {{ $patient->apellidos }}</p>
                                                        <p class="text-xs text-gray-500">{{ $patient->codigo }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-8 text-center">
                                    <p class="text-gray-500">No hay pacientes asignados a este terapeuta</p>
                                </div>
                            @endif
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-event-layout>
