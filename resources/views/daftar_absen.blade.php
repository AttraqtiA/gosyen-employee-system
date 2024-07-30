<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Absen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div
                        class="flex items-center justify-between flex-col flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('daftar_absen') }}" class="mb-4">
                            <label for="table-search-users" class="sr-only">Search</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="table-search-users" name="search"
                                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Cari nama user" value="{{ request('search') }}">
                            </div>
                        </form>

                        <div class="text-lg italic text-center p-2 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse(now()->toDateString())->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Masuk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Keluar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Bukti Foto
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($daftar_user_day->count() == 0 && $not_hadir_users->count() == 0)
                                <tr>
                                    <td colspan="6" class="p-4 text-center">
                                        <p class="text-gray-400">Yah, hasil tidak ditemukan...</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($daftar_user_day as $absen_info)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                        <th scope="row"
                                            class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <img class="w-10 h-10 rounded-full" src="{{ '' }}"
                                                alt="Profile Picture">
                                            <div class="ps-3">
                                                <div class="text-base font-semibold">{{ $absen_info->user->name }}</div>
                                                <div class="font-normal text-gray-500">{{ $absen_info->user->email }}
                                                </div>
                                            </div>
                                        </th>
                                        @if ($absen_info->user->role == 1)
                                            <td class="px-6 py-4">
                                                Owner
                                            </td>
                                        @elseif ($absen_info->user->role == 2)
                                            <td class="px-6 py-4">
                                                Supervisor
                                            </td>
                                        @elseif ($absen_info->user->role == 3)
                                            <td class="px-6 py-4">
                                                Member
                                            </td>
                                        @endif

                                        @if ($absen_info->status == 'Hadir' || $absen_info->status == 'Pulang')
                                            <td class="px-6 py-4">
                                                <p class="text-green-500 dark:text-green-400">
                                                    {{ $absen_info->status }}
                                                </p>
                                            </td>
                                        @elseif ($absen_info->status == 'Terlambat' || $absen_info->status == 'Pulang Cepat')
                                            <td class="px-6 py-4">
                                                <p class="text-red-500 dark:text-red-400">
                                                    {{ $absen_info->status }}
                                                </p>
                                            </td>
                                        @endif

                                        <td class="px-6 py-4">
                                            <p class="text-gray-900 dark:text-white">
                                                {{ $absen_info->time_in }}
                                            </p>
                                        </td>

                                        @if ($absen_info->time_out == null)
                                            <td class="px-6 py-4">
                                                <p class="text-blue-500 dark:text-blue-400">
                                                    Masih Kerja
                                                </p>
                                            </td>
                                        @else
                                            <td class="px-6 py-4">
                                                <p class="text-gray-900 dark:text-white">
                                                    {{ $absen_info->time_out }}
                                                </p>
                                            </td>
                                        @endif

                                        <td class="px-6 py-4">
                                            <button type="button"
                                                data-modal-target="bukti_foto{{ $absen_info->user->id }}"
                                                data-modal-toggle="bukti_foto{{ $absen_info->user->id }}"
                                                class="py-2 px-3 flex items-center text-sm font-medium text-center text-gray-600 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24"
                                                    fill="currentColor" class="w-4 h-4 mr-2 -ml-0.5">
                                                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                                </svg>
                                                Lihat
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach



                                {{-- modalnya nongol cuman kalau ada yg search include user hadir --}}
                                @if (!$daftar_user_day->isEmpty())
                                    <div id="bukti_foto{{ $absen_info->user->id }}" tabindex="-1" aria-hidden="true"
                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] md:h-full">
                                        <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
                                            <!-- Modal content -->
                                            <div id="update-modal-content"
                                                class="relative p-4 bg-white rounded-lg shadow sm:p-5">
                                                <!-- Modal header -->
                                                <div
                                                    class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        Bukti Foto Absen: {{ $absen_info->user->name }}</h3>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                        data-modal-toggle="bukti_foto{{ $absen_info->user->id }}">
                                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                            viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="sr-only">Tutup</span>
                                                    </button>
                                                </div>
                                                <!-- Modal body -->

                                                @if ($absen_info->proof_photo != null || $absen_info->proof_photo != '')
                                                    <img src="{{ asset('storage/' . $absen_info->proof_photo) }}"
                                                        alt="{{ asset('storage/' . $absen_info->proof_photo) }}"
                                                        class="mt-3 w-96 mx-auto rounded-lg object-cover">
                                                @else
                                                    <p
                                                        class="mt-3 text-red-700 text-center font-semibold text-gray-900">
                                                        Somehow No Bukti Foto?!</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- BELUM ABSEN KLUBB ------------------------------------------------------------------------------ --}}

                                @foreach ($not_hadir_users as $ngilang_info)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                        <th scope="row"
                                            class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <img class="w-10 h-10 rounded-full" src="{{ '' }}"
                                                alt="Profile Picture">
                                            <div class="ps-3">
                                                <div class="text-base font-semibold">{{ $ngilang_info->name }}</div>
                                                <div class="font-normal text-gray-500">{{ $ngilang_info->email }}
                                                </div>
                                            </div>
                                        </th>
                                        @if ($ngilang_info->role == 1)
                                            <td class="px-6 py-4">
                                                Owner
                                            </td>
                                        @elseif ($ngilang_info->role == 2)
                                            <td class="px-6 py-4">
                                                Supervisor
                                            </td>
                                        @elseif ($ngilang_info->role == 3)
                                            <td class="px-6 py-4">
                                                Member
                                            </td>
                                        @endif

                                        <td class="px-6 py-4">
                                            <p class="text-red-500 dark:text-red-400">
                                                Tidak Hadir
                                            </p>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="text-gray-900 dark:text-white">
                                                -
                                            </p>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="text-gray-900 dark:text-white">
                                                -
                                            </p>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="text-red-500 dark:text-red-400">
                                                NO FOTO
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                    </table>

                </div>

                {{-- masih blm include yg sdh absen (?) --}}
                <nav class="flex flex-col md:flex-row justify-end items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation">
                    {{ $not_hadir_users->links('vendor.pagination.tailwind') }}
                </nav>

            </div>
        </div>
    </div>
</x-app-layout>
