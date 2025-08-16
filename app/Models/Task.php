<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public static function tugasBelumSelesai(){
        return Task::select(['id', 'title', 'description'])
            ->where('status', 'belum_dimulai')
            ->orderBy('id', 'asc')
            ->get();
    }
}
