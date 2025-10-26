<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historial de estados de ánimo - {{ $patient->nombres }} {{ $patient->apellidos }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg p-6">
                @if($moods->isEmpty())
                    <div class="text-center py-8 text-gray-600">No hay registros todavía.</div>
                @else
                    @php
                        // Preparar arrays para la gráfica

                        // Escala fija de emociones (orden deseado)
                        $orderedEmotions = ['Muy triste', 'Triste', 'Neutral', 'Contento', 'Muy feliz'];
                        // Crear mapeo emoción => índice (1..N) según la escala fija
                        $emotionIndex = [];
                        foreach ($orderedEmotions as $i => $emo) {
                            $emotionIndex[$emo] = $i + 1;
                        }

                        // Construir pares (date, y) y ordenar por fecha ascendente (más antigua a la izquierda)
                        $entries = [];
                        foreach ($moods as $m) {
                            $date = \Carbon\Carbon::parse($m->recorded_at)->format('Y-m-d');
                            $value = $emotionIndex[$m->mood] ?? $emotionIndex['Neutral'];
                            $entries[] = ['date' => $date, 'y' => $value];
                        }

                        usort($entries, function($a, $b) {
                            return strcmp($a['date'], $b['date']);
                        });

                        $dates = array_column($entries, 'date');
                        $yValues = array_column($entries, 'y');

                        // Usar las etiquetas ordenadas directamente
                        $uniqueEmotions = $orderedEmotions;
                    @endphp

                    <div class="w-full" style="height:360px;">
                        <canvas id="moodChart" style="width:100%; height:100%; display:block;"></canvas>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        (function(){
                            const labels = {!! json_encode($dates) !!};
                            const yData = {!! json_encode($yValues) !!};
                            const emotionLabels = {!! json_encode($uniqueEmotions) !!};

                            const ctx = document.getElementById('moodChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Estado',
                                        data: yData,
                                        fill: false,
                                        borderColor: 'rgb(34,197,94)',
                                        backgroundColor: 'rgb(16,185,129)',
                                        tension: 0.2,
                                        pointRadius: 6
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            title: { display: true, text: 'Fecha' }
                                        },
                                        y: {
                                            title: { display: true, text: 'Emoción' },
                                            ticks: {
                                                stepSize: 1,
                                                callback: function(value) {
                                                    const idx = value - 1;
                                                    return emotionLabels[idx] ?? value;
                                                }
                                            },
                                            min: 1,
                                            max: Math.max(1, emotionLabels.length)
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            callbacks: {
                                                label: function(ctx) {
                                                    const v = ctx.parsed.y;
                                                    return emotionLabels[v - 1] ?? v;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        })();
                    </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
