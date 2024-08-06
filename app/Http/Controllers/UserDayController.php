<?php

namespace App\Http\Controllers;

use App\Models\User_Day;
use App\Models\User;
use App\Http\Requests\StoreUser_DayRequest;
use App\Http\Requests\UpdateUser_DayRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
// use Stevebauman\Location\Facades\Location;

class UserDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index() // absen lur
    {
        $today = now()->toDateString();

        // memang MERAH ini, tp work ya :")
        $sudahAbsenToday = Auth::user()->user_day()->where('date', $today)->exists();
        $userDay = Auth::user()->user_day()->where('date', $today)->first();

        if (!$sudahAbsenToday) { // ! - belum absen
            return view('absen', [
                'sudah_absen' => $sudahAbsenToday,
                'user_day' => null,
            ]);
        } else {
            return view('absen', [
                'sudah_absen' => $sudahAbsenToday,
                'user_day' => $userDay,
            ]);
        }
    }

    public function daftar_absen(Request $request)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $selectedDate = $request->input('date', now()->toDateString());

            $search = $request->input('search');

            $usersWithRecords = User_Day::where('date', $selectedDate)
                ->pluck('user_id');

            $usersWithNoRecords = User::whereNotIn('id', $usersWithRecords)
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->get();

            $daftar_user_day = User_Day::where('date', $selectedDate)
                ->when($search, function ($query, $search) {
                    return $query->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
                })
                ->get();

            $order = [
                'Pulang Cepat' => 1,
                'Pulang' => 2,
                'Terlambat' => 3,
                'Hadir' => 4,
            ];

            $daftar_user_day = $daftar_user_day->sort(function ($a, $b) use ($order) {
                return $order[$a->status] <=> $order[$b->status];
            });

            return view('daftar_absen', [
                'daftar_user_day' => $daftar_user_day,
                'not_hadir_users' => $usersWithNoRecords,
                'selectedDate' => $selectedDate,
            ]);
        } else {
            return redirect()->route('welcome');
        }
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'proof_photo' => 'image|mimes:jpeg,png,jpg|max:5000', // ini untuk validasi file image
            'description' => 'nullable|string',
        ]);

        $user_id = Auth::user()->id;
        $date = now();
        $time_in = now();

        if ($time_in->format('H:i:s') <= '08:00:00') {
            $status = 'Hadir';
        } else {
            $status = 'Terlambat';
        }

        $latitude = $validatedData['latitude'];
        $longitude = $validatedData['longitude'];

        // Use Google Maps Geocoding API
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Store your API key in the .env file

        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey,
        ]);

        $address = '';
        if ($response->successful() && isset($response['results'][0])) {
            $address = $response['results'][0]['formatted_address'];
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to retrieve address.']);
        }

        // php artisan storage:link, tapi untuk di Hostinger need to replace htaccess dan filesystems.php, contact me for the code!
        $validatedData['proof_photo'] = $request->file('proof_photo')->store('absen_images', ['disk' => 'public']);
        // disk public ini untuk membuat folder upload_images di folder storage/app/public
        // function store ini akan memasukkan gambar ke dalam storage/public/nama_folder_image
        // dengan nama file yang sudah merupakan string random sehingga memungkinkan kita untuk memasukkan file gambar dengan nama yang sama tapii beda gambar.

        User_Day::create([
            'user_id' => $user_id,
            'date' => $date,
            'time_in' => $time_in,
            'time_out' => null,
            'status' => $status,
            'proof_photo' => $validatedData['proof_photo'],
            'description' => $validatedData['description'],
            'address' => $address,
        ]);

        return redirect()->route('dashboard')->with('success', 'Absen berhasil');
    }

    /**
     * Display the specified resource.
     */
    public function show(User_Day $user_Day)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User_Day $user_Day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User_Day $user_Day)
    {
        $time_out = now();
        $date = now();
        $dayOfWeek = $date->dayOfWeek; // aslinya bisa pakai time_now, tp biar ga bingung aja~

        // Check if it's Saturday, kok nggak libur :(
        if ($dayOfWeek == Carbon::SATURDAY) {
            $cutoffTime = '14:00:00';
        } else {
            $cutoffTime = '17:00:00';
        }

        if ($time_out->format('H:i:s') >= $cutoffTime) {
            $status = 'Pulang';
        } else {
            $status = 'Pulang Cepat';
        }

        $user_Day->update([
            'time_out' => $time_out,
            'status' => $status,
        ]);

        return redirect()->route('welcome'); // udah pulang yee
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User_Day $user_Day)
    {
        //
    }
}

// BEKAS FIND ADDRESS BASED ON IP ADDRESS

// $ip = $request->ip(); // Static IP: $ip = '162.159.24.227'
// dd($ip);

// // Using IPinfo API dari ipinfo.io to get location data
// $response = Http::get("https://ipinfo.io/{$ip}/geo", [
//     'token' => env('IPINFO_API_KEY'),
// ]);

// $locationData = $response->json();
// $latLng = explode(',', $locationData['loc']);
// $latitude = $latLng[0];
// $longitude = $latLng[1];

// // Use Google Maps Geocoding API
// $apiKey = env('GOOGLE_MAPS_API_KEY');

// $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
//     'latlng' => "$latitude,$longitude",
//     'key' => $apiKey,
// ]);

// $address = '';
// if ($response->successful() && isset($response['results'][0])) {
//     $address = $response['results'][0]['formatted_address'];
// }
