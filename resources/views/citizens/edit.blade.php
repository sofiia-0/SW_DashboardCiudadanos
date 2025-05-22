<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Edit Citizen') }}
            </h2>
            <a href="{{ route('citizens.index') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-base font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-14 bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-blue-950 min-h-screen">
        <div class="max-w-xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8">
                <h3 class="text-xl font-semibold text-indigo-700 dark:text-indigo-300 mb-6">{{ __('Update Citizen Information') }}</h3>
                <form method="POST" action="{{ route('citizens.update', $citizen->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $citizen->first_name) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $citizen->last_name) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Birth Date') }}</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $citizen->birth_date) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                    </div>

                    <div>
                        <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('City') }}</label>
                        <select name="city_id" id="city_id" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                            <option value="">{{ __('Select City') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $citizen->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Address') }}</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $citizen->address) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Phone') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $citizen->phone) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600 transition" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-md transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                            {{ __('Update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
