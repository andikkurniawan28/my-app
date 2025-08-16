<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Project;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome');
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

    public function jadwalMenunggu(){
        return Schedule::jadwalMenunggu();
    }

    public function proyekBelumDimulai(){
        return Project::proyekBelumDimulai();
    }

    public function tugasBelumSelesai(){
        return Task::tugasBelumSelesai();
    }

    public function selesaikanTugas(Request $request) {
        Task::whereId($request->id)->update(['status' => 'selesai']);
        return redirect()->back()->with('success', 'Tugas berhasil diselesaikan.');
    }

}
