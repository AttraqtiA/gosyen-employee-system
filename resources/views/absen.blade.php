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
                    <div class="flex flex-row justify-center items-center gap-4">
                        <img src="https://img.icons8.com/?size=100&id=21181&format=png&color=000000" alt="Check Icon"
                            class="w-10 h-10 mt-1">
                        <div class="font-bold text-center py-6 text-xl text-red-500 dark:text-red-400">
                            {{ __('Anda belum absen hari ini!') }}
                        </div>
                    </div>

                    <form id="absenForm" action="{{ route('absen.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex flex-col items-center">
                        @csrf
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div id="imagePreview" class="mb-3 w-1/2 mx-auto"></div>

                        <div class="flex justify-center items-center w-full">
                            <label for="proof_photo"
                                class="flex flex-col justify-center items-center mt-4 w-3/4 md:w-1/2 h-44 bg-gray-300 dark:bg-gray-50 rounded-lg border-1 border-gray-300 border-dashed cursor-pointer hover:bg-gray-200">

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
                                    <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Ukuran File MAX. 5MB)</p>
                                </div>

                            </label>
                        </div>

                        <!-- Keterangan Text Field -->
                        <div class="w-3/4 mt-8">
                            <textarea id="description" name="description" rows="4"
                                class="block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Masukkan Keterangan [Opsional]"></textarea>
                        </div>

                        <button type="button" onclick="getUserLocation()" id="submitButton"
                            class="cursor-pointer my-8 w-3/4 md:w-1/2 text-white bg-blue-500 font-medium rounded-lg text-base px-5 py-2.5 text-center items-center">
                            ABSEN
                        </button>
                    </form>
                @else
                    <div class="flex flex-row justify-center items-center gap-4">
                        <img src="https://img.icons8.com/?size=100&id=19Qs7U6PcAie&format=png&color=000000"
                            alt="Check Icon" class="w-10 h-10 mt-1">
                        <div class="font-bold text-center py-6 text-xl text-green-500 dark:text-green-400">
                            {{ __('Anda sudah absen hari ini!') }}
                        </div>
                    </div>
                    <div class="text-lg italic text-center p-2 text-gray-900 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($user_day->date)->translatedFormat('l, d F Y') }}
                    </div>

                    @if ($user_day->status == 'Hadir')
                        <div class="text-center p-2 text-green-500 dark:text-green-400">
                            {{ 'Masuk: ' . $user_day->time_in . ' - ' . $user_day->status }}
                        </div>
                    @elseif($user_day->status == 'Terlambat')
                        <div class="text-center p-2 text-red-500 dark:text-red-400">
                            {{ 'Masuk: ' . $user_day->time_in . ' - ' . $user_day->status }}
                        </div>
                    @else
                        <div class="text-center p-2 text-gray-900 dark:text-gray-100">
                            {{ 'Masuk: ' . $user_day->time_in }}
                        </div>
                    @endif


                    @if ($user_day->time_out == null)
                        <div class="text-center p-2 text-blue-500 dark:text-blue-400">
                            {{ 'Pulang: Masih Kerja' }}
                        </div>

                        <form action="{{ route('absen.update', $user_day) }}" method="POST"
                            enctype="multipart/form-data" class="flex flex-col items-center">
                            @method('put')
                            @csrf

                            <button type="submit"
                                class="cursor-pointer my-8 w-3/4 md:w-1/2 text-white bg-orange-500 font-medium rounded-lg text-base px-5 py-2.5 text-center items-center">
                                PULANG
                            </button>
                        </form>
                    @else
                        @if ($user_day->status == 'Pulang')
                            <div class="text-center p-2 text-green-500 dark:text-green-400">
                                {{ 'Pulang: ' . $user_day->time_out . ' - ' . $user_day->status }}
                            </div>
                        @elseif($user_day->status == 'Pulang Cepat')
                            <div class="text-center p-2 text-red-500 dark:text-red-400">
                                {{ 'Pulang: ' . $user_day->time_out . ' - ' . $user_day->status }}
                            </div>
                        @endif
                    @endif

                    @if ($user_day->description != null)
                        <div class="text-center text-gray-900 dark:text-gray-100">
                            {{ 'Keterangan: ' . $user_day->description }}
                        </div>
                    @endif

                    <img src="{{ asset('storage/' . $user_day->proof_photo) }}" alt="Bukti Absen"
                        class="w-1/2 mt-4 mx-auto rounded-lg object-cover">

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


        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(success, error);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function success(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Set the values in hidden input fields
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            // Automatically submit the form once coordinates are set
            document.getElementById('absenForm').submit();
        }

        function error() {
            alert("Unable to retrieve your location.");
        }
    </script>
</x-app-layout>
