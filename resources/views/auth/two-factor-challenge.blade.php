<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __(!$recovery ? 'Please confirm access to your account by entering the authentication code provided by authenticator app.' : 'Please confirm access to your account by entering the recovery code provided by 2FA') }}
    </div>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        @if (!$recovery)
            <!-- Authentication Code -->
            <div class="mt-4">
                <x-input-label for="code" :value="__('Code')" />

                <x-text-input id="code" class="block mt-1 w-full"
                                type="text"
                                name="code"
                                inputmode="numeric"
                                autofocus
                                autocomplete="one-time-code" />

                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>
        @else
            <!-- Recovery Code -->
            <div class="mt-4">
                <x-input-label for="recovery_code" :value="__('Recovery Code')" />

                <x-text-input id="code" class="block mt-1 w-full"
                                type="text"
                                name="recovery_code"
                                autofocus
                                autocomplete="one-time-code" />

                <x-input-error :messages="$errors->get('recovery_code')" class="mt-2" />
            </div>
        @endif

        <div class="flex flex-center justify-end mt-4">

            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ $recovery ? route('two-factor.login') : route('two-factor.login', ['recovery' => true]) }}">
                {{ __(!$recovery ? 'Use Recovery Code' : 'Use Authentication Code') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
