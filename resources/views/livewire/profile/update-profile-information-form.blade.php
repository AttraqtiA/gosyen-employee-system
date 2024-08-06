<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

use function Livewire\Volt\state;

state([
    'name' => fn() => auth()->user()->name,
    'email' => fn() => auth()->user()->email,
    'profile_picture' => fn() => auth()->user()->profile_picture,
]);

$updateProfileInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5000'],
    ]);

    dd($validated);
    if ($this->profile_picture) {
        $validated['profile_picture'] = $this->profile_picture->store('profile_picts', 'public');
    }

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    $this->dispatch('profile-updated', name: $user->name);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Info Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update info nama dan emailmu!') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6 w-full">
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input wire:model="name" id="name" name="name" type="text"
                class="mt-1 block w-full text-gray-800 dark:text-white" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email"
                class="mt-1 block w-full text-gray-800 dark:text-white" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Foto Profil')" />

            <div id="imagePreview" class="w-1/2 mt-2 mx-auto"></div>

            <div class="flex w-full">
                <label for="profile_picture"
                    class="flex flex-col mt-4 w-full h-44 bg-gray-300 dark:bg-gray-50 rounded-lg border-1 border-gray-300 border-dashed cursor-pointer hover:bg-gray-200">

                    {{-- Ini ada wire:modelnya --}}
                    <input wire:model="profile_picture" type="file" name="profile_picture" id="profile_picture"
                        class="hidden" onchange="displayImagePreview(this); checkFileInput();">

                    <div class="flex flex-col justify-center items-center w-full pt-5 pb-6">
                        <svg aria-hidden="true" class="mb-3 w-10 h-10 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Klik untuk upload</span>
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Ukuran File MAX. 5MB)</p>
                    </div>

                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary2-button>{{ __('Save') }}</x-primary2-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Sudah diupdate yaa~') }}
            </x-action-message>
        </div>

    </form>

    <script>
        // Function to display the uploaded image preview
        function displayImagePreview(input) {
            var preview = document.getElementById('imagePreview');

            // Ensure preview div exists
            if (!preview) {
                console.error('Preview element with id "imagePreview" not found.');
                return;
            }

            // Remove existing image preview
            preview.innerHTML = '';

            // Check if file input has files and display the new image
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.setAttribute('src', e.target.result);
                    img.classList.add('w-6/12', 'mx-auto', 'rounded-lg', 'object-cover');
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</section>
