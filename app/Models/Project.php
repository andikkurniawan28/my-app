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
    // Proyek Belum Dimulai
    public static function proyekBelumDimulai()
    {
        return Project::select(['id', 'title', 'description', 'deadline'])
            ->where('status', 'belum_dimulai')
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($item) {
                $item->deadline = Carbon::parse($item->deadline)->format('d/m/Y');
                return $item;
            });
    }
}
