<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Citizens') }}
            </h2>
            <a href="{{ route('citizens.create') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-base font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                {{ __('New Citizen') }}
            </a>
        </div>
    </x-slot>

    <div class="py-14 bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-blue-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-10 items-stretch">
                @foreach ($citizens as $citizen)
                    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 flex flex-col justify-between border border-gray-100 dark:border-gray-800 hover:scale-[1.03] hover:shadow-2xl transition-all duration-200 h-full">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 tracking-wide">
                                {{ $citizen->first_name }} {{ $citizen->last_name }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-300 mb-5 text-base">
                                <strong>{{ __('Birth Date:') }}</strong> {{ $citizen->birth_date ? \Carbon\Carbon::parse($citizen->birth_date)->format('d/m/Y') : 'N/A' }}
                            </p>
                            <p class="text-gray-500 dark:text-gray-300 mb-5 text-base">
                                <strong>{{ __('City:') }}</strong> {{ $citizen->city->name ?? 'No city' }}
                            </p>
                            <p class="text-gray-500 dark:text-gray-300 mb-5 text-base">
                                <strong>{{ __('Address:') }}</strong> {{ $citizen->address ?? 'No address specified' }}
                            </p>
                            <p class="text-gray-500 dark:text-gray-300 mb-5 text-base">
                                <strong>{{ __('Phone:') }}</strong> {{ $citizen->phone ?? 'No phone specified' }}
                            </p>
                        </div>
                        <div class="flex space-x-3 mt-4">
                            <a href="{{ route('citizens.edit', $citizen->id) }}" class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-xl font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition shadow">
                                {{ __('Edit') }}
                            </a>

                            @if (!request()->has('confirm_delete') || request('confirm_delete') != $citizen->id)
                                <a href="{{ route('citizens.index', ['confirm_delete' => $citizen->id]) }}" class="px-4 py-2 text-red-600 dark:text-red-400 hover:underline rounded-xl transition">
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
                                    <a href="{{ route('citizens.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                        {{ __('No') }}
                                    </a>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-10 flex justify-center">
            {{ $citizens->links() }}
        </div>
    </div>
</x-app-layout>
