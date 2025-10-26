<x-crud-layout>
    <x-slot name="title">{{ $activity->name }}</x-slot>

    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">{{ $activity->name }}</h1>

        <p class="mb-4">{{ $activity->description }}</p>

        <form action="{{ route('patient-moods.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-2">Selecciona tu estado de ánimo</label>

                @php
                    $moodsList = [
                        ['key' => 'Muy triste', 'emoji' => '😢', 'color' => 'red-600'],
                        ['key' => 'Triste', 'emoji' => '🙁', 'color' => 'orange-500'],
                        ['key' => 'Neutral', 'emoji' => '😐', 'color' => 'yellow-400'],
                        ['key' => 'Contento', 'emoji' => '😊', 'color' => 'lime-500'],
                        ['key' => 'Muy feliz', 'emoji' => '😁', 'color' => 'green-600'],
                    ];
                    $current = old('mood', 'Neutral');
                @endphp

                <input type="hidden" name="mood" id="mood_input" value="{{ $current }}">

                <div id="moodsRow" class="flex flex-row items-stretch gap-3">
                    @foreach($moodsList as $m)
                        <div class="mood-item flex items-center gap-3 px-4 py-3 border rounded-lg cursor-pointer select-none"
                             data-value="{{ $m['key'] }}" tabindex="0" role="button" aria-pressed="false">
                            <span class="text-2xl">{{ $m['emoji'] }}</span>
                            <span class="text-sm font-medium">{{ $m['key'] }}</span>
                        </div>
                    @endforeach
                </div>

                <script>
                    (function(){
                        const row = document.getElementById('moodsRow');
                        const input = document.getElementById('mood_input');
                        if (!row || !input) return;

                        function selectItem(el){
                            // clear previous
                            row.querySelectorAll('.mood-item').forEach(i => {
                                i.classList.remove('bg-gray-100','ring','ring-2','ring-offset-2');
                                i.setAttribute('aria-pressed','false');
                            });
                            el.classList.add('bg-gray-100','ring','ring-2','ring-offset-2');
                            el.setAttribute('aria-pressed','true');
                            input.value = el.dataset.value;
                        }

                        // Initialize selection from input value
                        const initial = input.value;
                        let initialized = false;
                        row.querySelectorAll('.mood-item').forEach(item => {
                            if (!initialized && item.dataset.value === initial) {
                                selectItem(item);
                                initialized = true;
                            }

                            item.addEventListener('click', () => selectItem(item));
                            item.addEventListener('keydown', (e) => {
                                if (e.key === 'Enter' || e.key === ' ') {
                                    e.preventDefault();
                                    selectItem(item);
                                }
                            });
                        });
                    })();
                </script>

                @error('mood')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <x-button>Enviar</x-button>
            </div>
        </form>
    </div>
</x-crud-layout>
