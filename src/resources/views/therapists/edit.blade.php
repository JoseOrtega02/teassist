<x-event-layout>

    <x-slot name="title">
        {{ __('Editar Terapeuta') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Editar Terapeuta</h2>
                        <a href="{{ route('therapists.show', $therapist->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Volver
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Error!</strong>
                            <ul class="mt-2 ml-4 list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('therapists.update', $therapist->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Datos personales -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Datos Personales
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombres <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $therapist->nombres) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                                        Apellidos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $therapist->apellidos) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                                        DNI <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="dni" id="dni" value="{{ old('dni', $therapist->dni) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                        pattern="[0-9]{8}" maxlength="8">
                                </div>

                                <div>
                                    <label for="nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha de Nacimiento <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="nacimiento" id="nacimiento" value="{{ old('nacimiento', $therapist->nacimiento) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sexo <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sexo" id="sexo" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('sexo', $therapist->sexo) === 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo', $therapist->sexo) === 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Información de Contacto
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $therapist->email) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', $therapist->telefono) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dirección <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="direccion" id="direccion" rows="2" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('direccion', $therapist->direccion) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Actualizar cuenta de usuario (opcional) -->
                        @if($therapist->user)
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-indigo-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Actualizar Contraseña (Opcional)
                                </h3>

                                <p class="text-sm text-gray-600 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                                    Deja estos campos en blanco si no deseas cambiar la contraseña.
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email de Acceso
                                        </label>
                                        <input type="email" name="user_email" id="user_email" value="{{ old('user_email', $therapist->user->email) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre de Usuario
                                        </label>
                                        <input type="text" name="user_name" id="user_name" value="{{ old('user_name', $therapist->user->name) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nueva Contraseña
                                        </label>
                                        <input type="password" name="password" id="password"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                            minlength="8" placeholder="Dejar vacío para no cambiar">
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            Confirmar Nueva Contraseña
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                            minlength="8" placeholder="Dejar vacío para no cambiar">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('therapists.show', $therapist->id) }}"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold hover:bg-gray-400 transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700 transition">
                                Actualizar Terapeuta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-event-layout>
