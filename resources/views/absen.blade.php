<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if (!$sudah_absen)
                    <div class="p-6 text-center text-gray-900 dark:text-gray-100">
                        {{ __('Anda belum absen hari ini!') }}
                    </div>

                    <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div id="imagePreview" class="mb-3 w-1/2 mx-auto"></div>

                        <div class="flex justify-center items-center w-full">
                            <label for="proof_photo"
                                class="flex flex-col justify-center items-center w-full h-44 bg-gray-300 dark:bg-gray-50 rounded-lg border-1 border-gray-300 border-dashed cursor-pointer hover:bg-gray-200">

                                <input type="file" name="proof_photo" id="proof_photo" class="hidden"
                                    onchange="displayImagePreview(this); checkFileInput();">

                                <div class="flex flex-col justify-center items-center w-full pt-5 pb-6">
                                    <svg aria-hidden="true" class="mb-3 w-10 h-10 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Klik untuk upload</span>
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Ukuran File MAX. 5MB)
                                    </p>
                                </div>

                            </label>
                        </div>
                        <button type="submit" id="submitButton" disabled
                            class="cursor-pointer mt-3 w-full text-white bg-blue-500 font-medium rounded-lg text-base px-5 py-2.5 text-center items-center">
                            ABSEN
                        </button>
                    </form>
                @else
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('Anda sudah absen hari ini') }}
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ 'Tanggal: ' . $user_day->date }}
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ 'Status: ' . $user_day->status }}
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ 'Masuk: ' . $user_day->time_in }}
                    </div>
                    @if ($user_day->time_out == null)
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ 'Pulang: Belum' }}
                        </div>
                    @else
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ 'Pulang: ' . $user_day->time_out }}
                        </div>
                    @endif

                    <form action="{{ route('absen.update', $user_day) }}" method="POST" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                    </form>

                    <img src="{{ asset('storage/' . $user_day->proof_photo) }}" alt="Bukti Absen"
                        class="w-1/4 mx-auto rounded-lg object-cover">

                @endif
            </div>
        </div>
    </div>

    <script language="javascript">
        // buat display input file image preview
        function displayImagePreview(input) {
            var preview = document.getElementById('imagePreview');

            // Remove existing image
            preview.innerHTML = '';

            // Display newly uploaded image
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

        // function to check if file input is not empty
        function checkFileInput() {
            var fileInput = document.getElementById('proof_photo');
            var submitButton = document.getElementById('submitButton');

            if (fileInput.files.length > 0) {
                submitButton.removeAttribute('disabled');
            } else {
                submitButton.setAttribute('disabled', true);
            }
        }

        // Initial call to set the button state on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkFileInput();
        });
    </script>
</x-app-layout>
