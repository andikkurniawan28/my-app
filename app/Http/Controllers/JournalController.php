<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Journal::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    return \Carbon\Carbon::parse($row->date)->format('d/m/Y');
                })
                ->editColumn('debit', function ($row) {
                    return number_format($row->debit, 0, ',', '.'); // Format lokal Indonesia
                })
                ->editColumn('credit', function ($row) {
                    return number_format($row->credit, 0, ',', '.'); // Format lokal Indonesia
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('journals.show', $row->id);
                    $deleteUrl = route('journals.destroy', $row->id);
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-info">Detil</a>
                            <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('journals.index');
    }

    public function create()
    {
        $accounts = Account::all();
        return view('journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required',
            'account_id.*' => 'required|exists:accounts,id',
            'debit.*' => 'nullable|numeric',
            'credit.*' => 'nullable|numeric',
        ]);

        $totalDebit  = array_sum($request->debit ?? []);
        $totalCredit = array_sum($request->credit ?? []);

        // Validasi balance
        if ($totalDebit != $totalCredit) {
            return redirect()->back()
                ->withInput()
                ->with('failed', 'Debit & Credit tidak balance!');
        }

        DB::transaction(function () use ($request, $totalDebit, $totalCredit) {
            $journal = Journal::create([
                'date' => $request->date,
                'description' => $request->description,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
            ]);

            foreach ($request->account_id as $i => $accountId) {
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $accountId,
                    'debit' => $request->debit[$i] ?? 0,
                    'credit' => $request->credit[$i] ?? 0,
                ]);
            }
        });

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dibuat.');
    }

    public function show(Journal $journal)
    {
        $journal->load(['details.account']);

        return view('journals.show', compact('journal'));
    }

    public function destroy(Journal $journal)
    {
        $journal->delete();
        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dihapus.');
    }
}
