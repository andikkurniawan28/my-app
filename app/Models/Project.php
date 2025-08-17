<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Function untuk mengambil proyek yang belum dimulai
     */
    public static function proyekBelumDimulai()
    {
        return Project::select(['id', 'title', 'description', 'deadline'])
            ->where('status', '!=', 'selesai')
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($item) {
                // Set locale Indonesia
                $carbon = Carbon::parse($item->date)->locale('id');

                // Format dengan hari
                $item->deadline = $carbon->translatedFormat('l, d/m/Y');
                // contoh output: "Sabtu, 16/08/2025"
                return $item;
            });
    }
}
