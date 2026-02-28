<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back
                </a>
            </div>

            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Phone Number Section (IMPORTANT FOR M-PESA) -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-l-4 border-green-500">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📱 M-Pesa Phone Number</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        This phone number will receive the M-Pesa STK Push when you checkout.
                    </p>
                    
                    @if(auth()->user()->phone)
                        <div class="bg-green-50 p-4 rounded-lg mb-4">
                            <p class="text-green-800 font-medium">Current phone: <span class="text-lg">{{ auth()->user()->phone }}</span></p>
                        </div>
                    @else
                        <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                            <p class="text-yellow-800">⚠️ No phone number set. Please add your M-Pesa number.</p>
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('patch')
                        
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                                :value="old('phone', auth()->user()->phone)" 
                                placeholder="254712345678" 
                                required />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            <p class="text-sm text-gray-500 mt-1">Format: 254XXXXXXXXX (e.g., 254712345678)</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Phone') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>