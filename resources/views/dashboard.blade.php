<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 dark:text-white tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Cards con degradados --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700 p-6 rounded-xl shadow transition transform hover:scale-105 hover:shadow-lg text-white font-poppins">
                <h3 class="text-lg font-semibold">Total de Ciudades 🏙️</h3>
                <p class="text-3xl mt-2">{{ $totalCiudades }}</p>
            </div>

            <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-6 rounded-xl shadow transition transform hover:scale-105 hover:shadow-lg text-white font-poppins">
                <h3 class="text-lg font-semibold">Total de Ciudadanos 👤</h3>
                <p class="text-3xl mt-2">{{ $totalCiudadanos }}</p>
            </div>

            <div class="bg-gradient-to-r from-green-500 via-green-600 to-green-700 p-6 rounded-xl shadow transition transform hover:scale-105 hover:shadow-lg text-white font-poppins">
                <h3 class="text-lg font-semibold">Ciudadanos por Ciudad 🏘️</h3>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($ciudadanosPorCiudad as $ciudad)
                        <li>{{ $ciudad->name }}: {{ $ciudad->citizens_count }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Selector para elegir ciudad --}}
        <div class="mb-6">
            <label for="selectCiudad" class="block mb-2 font-semibold font-poppins text-gray-700 dark:text-gray-200">
            Selecciona una ciudad:
            </label>
            <div class="relative w-full md:w-1/3">
            <select id="selectCiudad"
                class="block appearance-none w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 py-3 px-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-poppins">
                <option value="">-- Elige una ciudad --</option>
                @foreach ($ciudades as $ciudad)
                <option value="ciudad-{{ $ciudad->id }}">{{ $ciudad->name }}</option>
                @endforeach
            </select>
            </div>
        </div>

        {{-- Contenedor de tablas --}}
        <div>
            @foreach ($ciudades as $ciudad)
                <div id="ciudad-{{ $ciudad->id }}" class="ciudad-tabla hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 transition-all duration-300">
                    <h3 class="text-2xl font-bold mb-6 font-poppins text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4a2 2 0 012-2h2a2 2 0 012 2v4m0 0h4m-4 0v-4m4 4v-4a2 2 0 012-2h2a2 2 0 012 2v4m0 0h-4"></path></svg>
                        {{ $ciudad->name }}
                    </h3>

                    @if ($ciudad->citizens->isEmpty())
                        <div class="flex items-center gap-2 text-gray-400 dark:text-gray-500 italic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 018 0v2m-4-4a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                            No hay ciudadanos registrados en esta ciudad.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-indigo-200 dark:divide-indigo-700">
                                <thead>
                                    <tr class="bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700">
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Apellido</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha de nacimiento</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($ciudad->citizens as $ciudadano)
                                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">{{ $ciudadano->first_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $ciudadano->last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                                {{ $ciudadano->birth_date ? \Carbon\Carbon::parse($ciudadano->birth_date)->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>

    <script>
        const selectCiudad = document.getElementById('selectCiudad');
        const tablas = document.querySelectorAll('.ciudad-tabla');

        selectCiudad.addEventListener('change', function () {
            tablas.forEach(tabla => tabla.classList.add('hidden')); 
            if (this.value) {
                const selected = document.getElementById(this.value);
                if (selected) {
                    selected.classList.remove('hidden'); 
                }
            }
        });
    </script>

</x-app-layout>