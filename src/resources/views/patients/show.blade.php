<x-crud-layout>
    <x-slot name="title">Detalle del paciente</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $patient->nombres }} {{ $patient->apellidos }}</h1>
                <p class="text-sm text-gray-500">Código: <span class="font-medium text-gray-700">{{ $patient->codigo }}</span></p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-sm rounded-md">
                    <!-- Back icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Volver
                </a>

                @can('patient-edit')
                    <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md">
                        Editar
                    </a>
                @endcan
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">DNI</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->dni }}</dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Fecha de nacimiento</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->nacimiento }}</dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->sexo }}</dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->telefono }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->email }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $patient->direccion }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                        <dd class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $patient->observaciones ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="border-t px-4 py-3 sm:px-6 flex items-center justify-end gap-3">
                @role('therapist')
                    <a href="{{ route('patients.moods.index', $patient) }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md">
                        Ver estados de ánimo
                    </a>
                @endrole

                @can('patient-delete')
                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Eliminar paciente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-md">Eliminar</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

</x-crud-layout>
