<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Account::insert([
        //     ['name' => 'Kas', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Rekening BCA', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Rekening BNI', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'e-Wallet GoPay', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'e-Wallet ShopeePay', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Piutang Usaha', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Piutang Lain-lain', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Tanah & Bangunan', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Emas', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Deposito', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Reksadana', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Obligasi', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Saham', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Valuta Mata Asing', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Mata Uang Kripto', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Sepeda Motor', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Mobil', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Sepeda', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Perangkat Elektronik', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Alat Musik', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Furnitur', 'category' => 'asset', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Hutang Usaha', 'category' => 'liability', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Hutang KPR', 'category' => 'liability', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Hutang PayLater', 'category' => 'liability', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Hutang Kartu Kredit BNI', 'category' => 'liability', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Hutang Lain-lain', 'category' => 'liability', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Modal', 'category' => 'equity', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Laba Ditahan', 'category' => 'equity', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Pendapatan Gaji', 'category' => 'revenue', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Pendapatan Usaha', 'category' => 'revenue', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Pendapatan Musik', 'category' => 'revenue', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Pendapatan Digital', 'category' => 'revenue', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Pendapatan Lain-lain', 'category' => 'revenue', 'description' => 'description', 'normal_balance' => 'credit'],
        //     ['name' => 'Biaya Makanan & Minuman', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Sewa Rumah', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Listrik', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Air', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Internet', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Paket Data', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Pendidikan', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Transport', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Pengasuhan', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Orang Tua', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Istri - Novia', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Anak - Adhyaksa', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Anak - Argantara', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Hiburan', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Usaha', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Investasi', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Perbaikan Kendaraan', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Perbaikan Rumah', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Pembantu Rumah Tangga', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Iuran', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Admin', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Donasi Saudara', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        //     ['name' => 'Biaya Lain-lain', 'category' => 'expense', 'description' => 'description', 'normal_balance' => 'debit'],
        // ]);

        User::insert([
            ['username' => 'admin', 'password' => bcrypt('password'), 'name' => 'Admin']
        ]);
    }
}
