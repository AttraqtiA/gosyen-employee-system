<?php

namespace App\Http\Controllers;

use App\Models\User_Day;
use App\Models\User;
use App\Http\Requests\StoreUser_DayRequest;
use App\Http\Requests\UpdateUser_DayRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index() // absen
     {
         $today = now()->toDateString(); // Get today's date

         // memang MERAH ini :")
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
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5000', // ini untuk validasi file image
        ]);

        $user_id = Auth::user()->id;
        $date = now();
        $time_in = now();

        if ($time_in->format('H:i:s') <= '08:00:00') {
            $status = 'Hadir';
        } else {
            $status = 'Terlambat';
        }

        // php artisan storage:link
        $validatedData['proof_photo'] = $request->file('proof_photo')->store('absen_images', ['disk' => 'public']);
        // disk public ini untuk membuat folder upload_images di folder storage/app/public
        // function store ini akan memasukkan gambar ke dalam storage/public/nama_folder_image
        // dengan nama file yang sudah merupakan string random sehingga memungkinkan kita untuk
        // memasukkan file gambar dengan nama yang sama tapii beda gambar.

        User_Day::create([
            'user_id' => $user_id,
            'date' => $date,
            'time_in' => $time_in,
            'time_out' => null,
            'status' => $status,
            'proof_photo' => $validatedData['proof_photo'],
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

        // Check if it's Saturday
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
