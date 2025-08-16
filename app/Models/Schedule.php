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
        return Schedule::select(['id', 'date', 'title'])
            ->where('status', 'menunggu')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d/m/Y');
                return $item;
            });
    }
}
