<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

use Illuminate\Support\Facades\Auth;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $userRole = Auth::user()->role;

    switch ($userRole) {
        case 1:
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            break;
        case 2:
            $this->redirectIntended(default: route('absen', absolute: false), navigate: true);
            break;
        case 3:
            $this->redirectIntended(default: route('absen', absolute: false), navigate: true);
            break;
        default:
            $this->redirect('/');
    }
};

?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full text-gray-800 dark:text-white"
                type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password"
                class="block mt-1 w-full text-gray-800 dark:text-white" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

            <div class="block mt-4">
                <label for="togglePassword" class="inline-flex items-center">
                    <input id="togglePassword" type="checkbox"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        name="togglePassword">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Lihat Password') }}</span>
                </label>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ingat saya') }}</span>
            </label>
        </div>


        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <x-primary2-button class="ms-3">
                {{ __('Log in') }}
            </x-primary2-button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordCheckbox = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');

        togglePasswordCheckbox.addEventListener('change', function (e) {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        });
    });
</script>

