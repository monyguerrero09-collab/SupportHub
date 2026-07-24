<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile photo.") }}
        </p>
    </header>

    <div class="mt-6 flex items-center space-x-6">
        @php
            $avatarPath = 'storage/avatars/perfil_' . auth()->id() . '.jpg';
            $hasAvatar = file_exists(public_path($avatarPath));
            // Add a timestamp to bypass browser cache
            $avatarUrl = $hasAvatar ? asset($avatarPath) . '?v=' . filemtime(public_path($avatarPath)) : null;
        @endphp

        <div class="shrink-0">
            @if($hasAvatar)
                <img class="h-16 w-16 object-cover rounded-full" src="{{ $avatarUrl }}" alt="Current profile photo" />
            @else
                <div class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            @endif
        </div>

        <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="flex-1">
            @csrf
            
            <label class="block">
                <span class="sr-only">Choose profile photo</span>
                <input type="file" name="photo" accept="image/jpeg, image/png, image/webp" class="block w-full text-sm text-gray-500 dark:text-gray-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100
                    dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800
                "/>
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />

            <div class="mt-4 flex items-center gap-4">
                <x-primary-button>{{ __('Save Photo') }}</x-primary-button>

                @if (session('status') === 'photo-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
