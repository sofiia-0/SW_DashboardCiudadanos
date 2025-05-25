<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Citizens') }}
            </h2>
            <a href="{{ route('citizens.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-base font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('New Citizen') }}
            </a>
        </div>
    </x-slot>
    
    @php
        $groupedCitizens = $citizens->groupBy(fn($citizen) => $citizen->city->name ?? __('No city'));
        $viewMode = request('view_mode', 'accordion');
    @endphp

    <div class="flex flex-col sm:flex-row justify-end items-center gap-4 mb-6 max-w-7xl mx-auto px-4 mt-8">
        <form method="GET" action="{{ route('citizens.index') }}" class="flex gap-2 w-full max-w-md">
            <input type="text" name="busqueda" placeholder="Buscar por nombre o ciudad" value="{{ request('busqueda') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-xl dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-400" />
            <button type="submit"
                class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                Buscar
            </button>
        </form>
        <form method="GET" action="{{ route('citizens.index') }}">
            <input type="hidden" name="page" value="{{ request('page', 1) }}">
            <select name="view_mode" onchange="this.form.submit()"
                class="rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 shadow focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer">
                <option value="accordion" {{ $viewMode == 'accordion' ? 'selected' : '' }}>{{ __('Accordion View') }}</option>
                <option value="table" {{ $viewMode == 'table' ? 'selected' : '' }}>{{ __('Table View') }}</option>
            </select>
        </form>
    </div>

    @if ($viewMode == 'table')
        <div class="px-4 sm:px-8">
            @foreach ($groupedCitizens->sortKeys() as $cityName => $cityCitizens)
                <div class="mb-10">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">{{ $cityName }}</h3>
                    <div class="overflow-x-auto rounded-2xl shadow border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Birth Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Address') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Phone') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($cityCitizens as $citizen)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">
                                            {{ $citizen->first_name }} {{ $citizen->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $citizen->birth_date ? \Carbon\Carbon::parse($citizen->birth_date)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $citizen->address ?? __('No address specified') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $citizen->phone ?? __('No phone specified') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('citizens.edit', $citizen->id) }}"
                                               class="inline-block px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-xl font-medium hover:bg-blue-700 dark:hover:bg-blue-800 transition shadow">
                                                {{ __('Edit') }}
                                            </a>
                                            @if (!request()->has('confirm_delete') || request('confirm_delete') != $citizen->id)
                                                <a href="{{ route('citizens.index', array_merge(request()->except('page'), ['confirm_delete' => $citizen->id])) }}"
                                                   class="inline-block px-4 py-2 text-red-600 dark:text-red-400 hover:underline rounded-xl transition">
                                                    {{ __('Delete') }}
                                                </a>
                                            @else
                                                <form action="{{ route('citizens.destroy', $citizen->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <span class="text-gray-700 dark:text-gray-200">{{ __('Are you sure?') }}</span>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">{{ __('Yes') }}</button>
                                                    <a href="{{ route('citizens.index', request()->except('confirm_delete')) }}"
                                                       class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                                        {{ __('No') }}
                                                    </a>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="space-y-8 px-4 sm:px-8">
            @foreach ($groupedCitizens->sortKeys() as $cityName => $cityCitizens)
            <div x-data="{ open: {{ request('confirm_delete') && $cityCitizens->contains('id', request('confirm_delete')) ? 'true' : 'false' }} }"
                 class="border border-gray-200 dark:border-gray-800 rounded-2xl bg-white dark:bg-gray-900 shadow transition-all duration-300">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-6 py-5 text-left text-xl font-semibold text-gray-900 dark:text-white focus:outline-none transition">
                <span>{{ $cityName }}</span>
                <svg :class="{'rotate-180': open}" class="w-6 h-6 text-gray-500 dark:text-gray-300 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
                </button>
                <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 max-h-0"
                 x-transition:enter-end="opacity-100 max-h-screen"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 max-h-screen"
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="px-6 pb-6"
                 style="overflow: hidden;">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-10 mt-4">
                    @foreach ($cityCitizens->sortBy(fn($citizen) => $citizen->first_name . ' ' . $citizen->last_name) as $citizen)
                    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 flex flex-col justify-between border border-gray-100 dark:border-gray-800 hover:scale-[1.03] hover:shadow-2xl transition-all duration-200 h-full">
                        <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 tracking-wide">
                            {{ $citizen->first_name }} {{ $citizen->last_name }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-300 mb-2">
                            <strong>{{ __('Birth Date:') }}</strong>
                            {{ $citizen->birth_date ? \Carbon\Carbon::parse($citizen->birth_date)->format('d/m/Y') : 'N/A' }}
                        </p>
                        <p class="text-gray-500 dark:text-gray-300 mb-2">
                            <strong>{{ __('Address:') }}</strong>
                            {{ $citizen->address ?? __('No address specified') }}
                        </p>
                        <p class="text-gray-500 dark:text-gray-300 mb-2">
                            <strong>{{ __('Phone:') }}</strong>
                            {{ $citizen->phone ?? __('No phone specified') }}
                        </p>
                        </div>
                        <div class="flex space-x-3 mt-4">
                        <a href="{{ route('citizens.edit', $citizen->id) }}"
                           class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-xl font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition shadow">
                            {{ __('Edit') }}
                        </a>
                        @if (!request()->has('confirm_delete') || request('confirm_delete') != $citizen->id)
                            <a href="{{ route('citizens.index', array_merge(request()->except('page'), ['confirm_delete' => $citizen->id])) }}"
                               class="px-4 py-2 text-red-600 dark:text-red-400 hover:underline rounded-xl transition">
                            {{ __('Delete') }}
                            </a>
                        @else
                            <form action="{{ route('citizens.destroy', $citizen->id) }}" method="POST" class="flex flex-wrap items-center space-x-2">
                            @csrf
                            @method('DELETE')
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ __('Are you sure?') }}
                            </span>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                                {{ __('Yes') }}
                            </button>
                            <a href="{{ route('citizens.index', request()->except('confirm_delete')) }}"
                               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                {{ __('No') }}
                            </a>
                            </form>
                        @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    <div class="mt-10 flex justify-center">
        {{ $citizens->links() }}
    </div>
</x-app-layout> 