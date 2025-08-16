<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Function untuk mengambil jadwal yang masih menunggu
     */
    public static function jadwalMenunggu()
    {
        return Schedule::select(['id', 'date', 'title', 'start_time', 'finish_time'])
            ->where('status', 'menunggu')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($item) {
                // Set locale Indonesia
                $carbon = Carbon::parse($item->date)->locale('id');

                // Format dengan hari
                $item->date = $carbon->translatedFormat('l, d/m/Y');
                // contoh output: "Sabtu, 16/08/2025"

                $item->start_time = Carbon::parse($item->start_time)->format('H:i');
                $item->finish_time = Carbon::parse($item->finish_time)->format('H:i');
                return $item;
            });

    }
}
