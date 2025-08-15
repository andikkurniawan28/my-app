<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function saldo()
    {
        $debit = $this->journalDetails()->sum('debit');
        $credit = $this->journalDetails()->sum('credit');

        if (strtolower($this->normal_balance) === 'debit') {
            return $debit - $credit;
        }

        return $credit - $debit;
    }
}
