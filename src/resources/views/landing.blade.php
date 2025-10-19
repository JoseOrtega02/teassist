<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TEAssist</title>
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased bg-pink-50 dark:bg-gray-900 dark:text-white/50">
    <header class="bg-yellow-100 dark:bg-yellow-900 border-b border-yellow-200 dark:border-yellow-800 py-8 mb-8">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-4">
          <h1 class="text-4xl md:text-5xl font-extrabold text-pink-500 dark:text-pink-300 mb-4 md:mb-0 tracking-tight">Software para Personas con Diagnóstico de Autismo</h1>
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="rounded px-4 py-2 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Panel</a>
                    @else
                        <a href="{{ route('patient.login.show') }}"
                           class="rounded px-4 py-2 bg-pink-500 text-white border border-pink-600 hover:bg-pink-600 transition">Ingresar</a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
    <section class="mb-12">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-3xl font-bold mb-4 text-blue-700 dark:text-blue-300">¿Qué es el Trastorno del Espectro Autista (TEA)?</h2>
                <p class="mb-4 text-gray-700 dark:text-gray-200">El Trastorno del Espectro Autista (TEA) es una condición caracterizada por presentar
                    variables alteraciones con un impacto de por vida. Estas manifestaciones son muy variables entre
                    individuos y a través del tiempo, acorde al crecimiento y maduración de las personas.</p>
                <img src="images/image_1.jpeg" alt="Representación del espectro autista" class="rounded-xl shadow-2xl mb-4 mx-auto w-full max-w-md">
            </div>
        </section>

        <section class="mb-12">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-3xl font-bold mb-4 text-blue-500 dark:text-blue-300">Características del Autismo</h2>
                <ul class="space-y-3 mb-4">
                <ul class="space-y-3 mb-4">
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-yellow-300 rounded-full mr-3 border-2 border-pink-400"></span>Dificultades en la comunicación</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-green-300 rounded-full mr-3 border-2 border-purple-400"></span>Dificultades en las interacciones sociales</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-pink-300 rounded-full mr-3 border-2 border-yellow-400"></span>Intereses restringidos</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-purple-300 rounded-full mr-3 border-2 border-green-400"></span>Repetición de comportamientos</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-orange-300 rounded-full mr-3 border-2 border-blue-400"></span>Sensibilidad sensorial</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-blue-200 rounded-full mr-3 border-2 border-orange-400"></span>Dificultades con el cambio</li>
                    <li class="flex items-center text-blue-900 dark:text-blue-200"><span class="inline-block w-4 h-4 bg-teal-300 rounded-full mr-3 border-2 border-pink-400"></span>Habilidades excepcionales en áreas específicas</li>
                </ul>
                <img src="images/image_2.jpeg" alt="Ilustración de características del autismo"
                    class="rounded-xl shadow-2xl mb-4 mx-auto w-full max-w-md">
            </div>
        </section>

        <section class="mb-12">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-3xl font-bold mb-4 text-green-500 dark:text-green-300">Nuestro Software</h2>
                <p class="mb-4 text-gray-700 dark:text-gray-200">Desarrollamos un software especializado para ayudar a personas con Trastorno del Espectro
                    Autista (TEA). Nuestro objetivo es proporcionar una herramienta que facilite la inclusión y mejore la
                    calidad de vida de las personas con TEA.</p>
                <h3 class="text-xl font-semibold mb-2 text-pink-500 dark:text-pink-300">Características del Software:</h3>
                <ul class="space-y-3 mb-4">
                <ul class="space-y-3 mb-4">
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-pink-300 rounded-full mr-3 border-2 border-yellow-400"></span>Interfaz amigable e intuitiva</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-yellow-300 rounded-full mr-3 border-2 border-pink-400"></span>Personalización según necesidades individuales</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-blue-300 rounded-full mr-3 border-2 border-green-400"></span>Comunicación visual</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-purple-300 rounded-full mr-3 border-2 border-blue-400"></span>Estructura y rutina claras</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-orange-300 rounded-full mr-3 border-2 border-purple-400"></span>Accesibilidad en diferentes dispositivos</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-teal-300 rounded-full mr-3 border-2 border-pink-400"></span>Colaboración con expertos en TEA</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-pink-200 rounded-full mr-3 border-2 border-green-400"></span>Privacidad y seguridad garantizadas</li>
                    <li class="flex items-center text-green-900 dark:text-green-200"><span class="inline-block w-4 h-4 bg-yellow-200 rounded-full mr-3 border-2 border-blue-400"></span>Actualizaciones y soporte continuo</li>
                </ul>
                <img src="images/image_3.jpeg" alt="Captura de pantalla del software" class="rounded-xl shadow-2xl mb-4 mx-auto w-full max-w-md">
            </div>
        </section>

        <section class="mb-12">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-3xl font-bold mb-4 text-orange-500 dark:text-orange-300">Beneficios de Nuestro Software</h2>
                <ul class="space-y-3 mb-4">
                <ul class="space-y-3 mb-4">
                    <li class="flex items-center text-orange-900 dark:text-orange-200"><span class="inline-block w-4 h-4 bg-green-300 rounded-full mr-3 border-2 border-pink-400"></span>Desarrollo de habilidades cognitivas, emocionales y motrices</li>
                    <li class="flex items-center text-orange-900 dark:text-orange-200"><span class="inline-block w-4 h-4 bg-yellow-300 rounded-full mr-3 border-2 border-blue-400"></span>Fomento de actividades interactivas para mejorar relaciones interpersonales</li>
                    <li class="flex items-center text-orange-900 dark:text-orange-200"><span class="inline-block w-4 h-4 bg-pink-300 rounded-full mr-3 border-2 border-green-400"></span>Complemento a la intervención terapéutica tradicional</li>
                    <li class="flex items-center text-orange-900 dark:text-orange-200"><span class="inline-block w-4 h-4 bg-blue-300 rounded-full mr-3 border-2 border-orange-400"></span>Contenido adaptable y personalizable</li>
                    <li class="flex items-center text-orange-900 dark:text-orange-200"><span class="inline-block w-4 h-4 bg-purple-300 rounded-full mr-3 border-2 border-yellow-400"></span>Retroalimentación inmediata y seguimiento del progreso</li>
                </ul>
                <img src="images/image_4.jpeg" alt="Personas utilizando el software" class="rounded-xl shadow-2xl mb-4 mx-auto w-full max-w-md">
            </div>
        </section>

        <section class="rounded-lg">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-teal-500 dark:text-teal-300">Contáctenos</h2>
                <p class="mb-2 text-gray-700 dark:text-gray-200">Para más información sobre nuestro software o para solicitar una demostración, por favor contáctenos:</p>
                <a href="mailto:info@autismosoftware.com" class="text-pink-600 dark:text-pink-300 font-semibold hover:underline">info@autismosoftware.com</a>
            </div>
        </section>
    </main>

    <footer class="bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-100 py-4 mt-12">
        <div class="container mx-auto px-4 text-center">
                <p class="text-xs text-gray-400">&copy; 2024 Software para Personas con Diagnóstico de Autismo. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>
