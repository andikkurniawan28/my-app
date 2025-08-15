<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function data()
    {
        $now = now();
        $thisMonth = $now->month;
        $thisYear = $now->year;

        $lastMonth = $now->copy()->subMonth();
        $lastMonthNum = $lastMonth->month;
        $lastMonthYear = $lastMonth->year;

        $results = DB::table('journal_details')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->join('accounts', 'journal_details.account_id', '=', 'accounts.id')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.category',
                'accounts.normal_balance',
                DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(journals.date) = {$thisMonth} AND YEAR(journals.date) = {$thisYear}
                        THEN
                            CASE
                                WHEN accounts.normal_balance = 'debit'
                                THEN journal_details.debit - journal_details.credit
                                ELSE journal_details.credit - journal_details.debit
                            END
                        ELSE 0
                    END
                ) as saldo_bulan_ini
            "),
                DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(journals.date) = {$lastMonthNum} AND YEAR(journals.date) = {$lastMonthYear}
                        THEN
                            CASE
                                WHEN accounts.normal_balance = 'debit'
                                THEN journal_details.debit - journal_details.credit
                                ELSE journal_details.credit - journal_details.debit
                            END
                        ELSE 0
                    END
                ) as saldo_bulan_lalu
            "),
                DB::raw("
                SUM(
                    CASE
                        WHEN accounts.normal_balance = 'debit'
                        THEN journal_details.debit - journal_details.credit
                        ELSE journal_details.credit - journal_details.debit
                    END
                ) as saldo_sd_saat_ini
            ")
            )
            ->groupBy('accounts.id', 'accounts.name', 'accounts.category', 'accounts.normal_balance')
            ->get();

        // Proses di PHP
        $neracaBulanIni = [];
        $neracaBulanLalu = [];
        $saldoKategori = [];
        $kategoriBulanIni = [];
        $kategoriBulanLalu = [];
        $biayaTerbesar = [];
        $pendapatanTerbesar = [];

        foreach ($results as $row) {
            $neracaBulanIni[$row->name] = $row->saldo_bulan_ini;
            $neracaBulanLalu[$row->name] = $row->saldo_bulan_lalu;

            $saldoKategori[$row->category] = ($saldoKategori[$row->category] ?? 0) + $row->saldo_sd_saat_ini;

            $kategoriBulanIni[$row->category] = ($kategoriBulanIni[$row->category] ?? 0) + $row->saldo_bulan_ini;
            $kategoriBulanLalu[$row->category] = ($kategoriBulanLalu[$row->category] ?? 0) + $row->saldo_bulan_lalu;

            if ($row->category === 'expense') {
                $biayaTerbesar[] = ['name' => $row->name, 'saldo' => $row->saldo_sd_saat_ini];
            }
            if ($row->category === 'revenue') {
                $pendapatanTerbesar[] = ['name' => $row->name, 'saldo' => $row->saldo_sd_saat_ini];
            }
        }

        // Urutkan daftar terbesar
        usort($biayaTerbesar, fn($a, $b) => $b['saldo'] <=> $a['saldo']);
        usort($pendapatanTerbesar, fn($a, $b) => $b['saldo'] <=> $a['saldo']);

        $labaRugi = ($kategoriBulanIni['revenue'] ?? 0) - ($kategoriBulanIni['expense'] ?? 0);
        $hutangPiutang = ($saldoKategori['asset'] ?? 0) - ($saldoKategori['liability'] ?? 0);

        return [
            'Neraca Bulan Ini' => $neracaBulanIni,
            'Neraca Bulan Lalu' => $neracaBulanLalu,
            'Laporan Laba Rugi' => $labaRugi,
            'Saldo Asset s/d Saat Ini' => $saldoKategori['asset'] ?? 0,
            'Saldo Hutang s/d Saat Ini' => $saldoKategori['liability'] ?? 0,
            'Saldo Pendapatan Bulan Ini' => $kategoriBulanIni['revenue'] ?? 0,
            'Saldo Pendapatan Bulan Lalu' => $kategoriBulanLalu['revenue'] ?? 0,
            'Saldo Biaya Bulan Ini' => $kategoriBulanIni['expense'] ?? 0,
            'Saldo Biaya Bulan Lalu' => $kategoriBulanLalu['expense'] ?? 0,
            'Daftar Biaya Terbesar' => array_slice($biayaTerbesar, 0, 5),
            'Daftar Pendapatan Terbesar' => array_slice($pendapatanTerbesar, 0, 5),
            'Hutang Piutang' => $hutangPiutang
        ];
    }

    public function neracaBulanLaluDanBulanIni()
    {
        $now = Carbon::now();

        $bulanIniStart = $now->copy()->startOfMonth();
        $bulanIniEnd   = $now->copy()->endOfMonth();

        $bulanLaluStart = $now->copy()->subMonth()->startOfMonth();
        $bulanLaluEnd   = $now->copy()->subMonth()->endOfMonth();

        return [
            'bulan_lalu' => $this->hitungNeraca($bulanLaluStart, $bulanLaluEnd),
            'bulan_ini'  => $this->hitungNeraca($bulanIniStart, $bulanIniEnd),
        ];
    }

    public function neracaSampaiDenganBulanLaluDanBulanIni()
    {
        $now = Carbon::now();

        // Ambil tanggal transaksi pertama (awal data)
        $awalData = DB::table('journals')->min('date');
        if (!$awalData) {
            return [
                'bulan_lalu' => [],
                'bulan_ini' => []
            ];
        }

        $awalData = Carbon::parse($awalData)->startOfDay();

        // Sampai dengan akhir bulan ini
        $bulanIniStart = $awalData;
        $bulanIniEnd   = $now->copy()->endOfMonth();

        // Sampai dengan akhir bulan lalu
        $bulanLaluStart = $awalData;
        $bulanLaluEnd   = $now->copy()->subMonth()->endOfMonth();

        return [
            'bulan_lalu' => $this->hitungNeraca($bulanLaluStart, $bulanLaluEnd),
            'bulan_ini'  => $this->hitungNeraca($bulanIniStart, $bulanIniEnd),
        ];
    }

    private function hitungNeraca($startDate, $endDate)
    {
        $accounts = DB::table('accounts')
            ->select('id', 'name', 'category', 'normal_balance')
            ->get();

        $hasil = [
            'asset'    => [],
            'liability'=> [],
            'equity'   => [],
            'revenue'  => [],
            'expense'  => [],
            'total_asset'    => 0,
            'total_liability'=> 0,
            'total_equity'   => 0,
            'total_revenue'  => 0,
            'total_expense'  => 0,
            'equity_adjusted'=> 0,
            'liability_plus_equity' => 0,
            'imbalance' => 0
        ];

        foreach ($accounts as $acc) {
            $saldo = DB::table('journal_details')
                ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                ->where('journal_details.account_id', $acc->id)
                ->whereBetween('journals.date', [$startDate, $endDate])
                ->selectRaw('
                    SUM(COALESCE(journal_details.debit,0)) as total_debit,
                    SUM(COALESCE(journal_details.credit,0)) as total_credit
                ')
                ->first();

            $totalDebit  = $saldo->total_debit ?? 0;
            $totalCredit = $saldo->total_credit ?? 0;

            // Hitung saldo sesuai normal_balance
            $nilai = ($acc->normal_balance === 'debit')
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;

            // Simpan ke kategori masing-masing
            $hasil[$acc->category][] = [
                'account' => $acc->name,
                'saldo'   => $nilai
            ];

            // Tambahkan total per kategori
            switch ($acc->category) {
                case 'asset':
                    $hasil['total_asset'] += $nilai;
                    break;
                case 'liability':
                    $hasil['total_liability'] += $nilai;
                    break;
                case 'equity':
                    $hasil['total_equity'] += $nilai;
                    break;
                case 'revenue':
                    $hasil['total_revenue'] += $nilai;
                    break;
                case 'expense':
                    $hasil['total_expense'] += $nilai;
                    break;
            }
        }

        // Hitung ekuitas yang disesuaikan (modal + pendapatan - biaya)
        $hasil['equity_adjusted'] = $hasil['total_equity'] + $hasil['total_revenue'] - $hasil['total_expense'];

        // Total liabilitas + ekuitas
        $hasil['liability_plus_equity'] = $hasil['total_liability'] + $hasil['equity_adjusted'];

        // Hitung imbalance
        $hasil['imbalance'] = $hasil['total_asset'] - $hasil['liability_plus_equity'];

        return $hasil;
    }

    public function pendapatanBebanBulanIni()
    {
        $now = \Carbon\Carbon::now();
        $start = $now->copy()->startOfMonth();
        $end   = $now->copy()->endOfMonth();

        // Ambil semua akun pendapatan
        $pendapatan = DB::table('accounts')
            ->select('accounts.id', 'accounts.name', 'accounts.normal_balance')
            ->where('accounts.category', 'revenue')
            ->get();

        // Ambil semua akun beban
        $beban = DB::table('accounts')
            ->select('accounts.id', 'accounts.name', 'accounts.normal_balance')
            ->where('accounts.category', 'expense')
            ->get();

        // Hitung saldo masing-masing akun pendapatan
        $pendapatanData = [];
        $totalPendapatan = 0;
        foreach ($pendapatan as $acc) {
            $saldo = DB::table('journal_details')
                ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                ->where('journal_details.account_id', $acc->id)
                ->whereBetween('journals.date', [$start, $end])
                ->selectRaw('
                    SUM(COALESCE(journal_details.debit,0)) as total_debit,
                    SUM(COALESCE(journal_details.credit,0)) as total_credit
                ')
                ->first();

            $totalDebit  = $saldo->total_debit ?? 0;
            $totalCredit = $saldo->total_credit ?? 0;

            $nilai = ($acc->normal_balance === 'debit')
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;

            $pendapatanData[] = [
                'account' => $acc->name,
                'saldo'   => $nilai
            ];

            $totalPendapatan += $nilai;
        }

        // Hitung saldo masing-masing akun beban
        $bebanData = [];
        $totalBeban = 0;
        foreach ($beban as $acc) {
            $saldo = DB::table('journal_details')
                ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                ->where('journal_details.account_id', $acc->id)
                ->whereBetween('journals.date', [$start, $end])
                ->selectRaw('
                    SUM(COALESCE(journal_details.debit,0)) as total_debit,
                    SUM(COALESCE(journal_details.credit,0)) as total_credit
                ')
                ->first();

            $totalDebit  = $saldo->total_debit ?? 0;
            $totalCredit = $saldo->total_credit ?? 0;

            $nilai = ($acc->normal_balance === 'debit')
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;

            $bebanData[] = [
                'account' => $acc->name,
                'saldo'   => $nilai
            ];

            $totalBeban += $nilai;
        }

        // Sort dan ambil top 5
        usort($pendapatanData, fn($a, $b) => $b['saldo'] <=> $a['saldo']);
        usort($bebanData, fn($a, $b) => $b['saldo'] <=> $a['saldo']);

        $topPendapatan = array_slice($pendapatanData, 0, 5);
        $topBeban = array_slice($bebanData, 0, 5);

        return response()->json([
            'total_pendapatan' => $totalPendapatan,
            'total_beban'      => $totalBeban,
            'top_pendapatan'   => $topPendapatan,
            'top_beban'        => $topBeban
        ]);
    }


}
