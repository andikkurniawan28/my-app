<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Account::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('saldo', function ($row) {
                    $saldo = $row->saldo();
                    return $saldo == 0 ? '-' : number_format($saldo, 0, ',', '.'); // format lokal
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('accounts.edit', $row->id);
                    $deleteUrl = route('accounts.destroy', $row->id);
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
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

        return view('accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $normalBalances = ['debit', 'credit'];

        return view('accounts.create', compact('categories', 'normalBalances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255|unique:accounts,name',
            'category'       => 'required|in:asset,liability,equity,revenue,expense',
            'description'    => 'required|string',
            'normal_balance' => 'required|in:debit,credit',
        ]);

        Account::create($request->only(['name', 'category', 'description', 'normal_balance']));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        $categories = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $normalBalances = ['debit', 'credit'];

        return view('accounts.edit', compact('account', 'categories', 'normalBalances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name'           => 'required|string|max:255|unique:accounts,name,' . $account->id,
            'category'       => 'required|in:asset,liability,equity,revenue,expense',
            'description'    => 'required|string',
            'normal_balance' => 'required|in:debit,credit',
        ]);

        $account->update($request->only(['name', 'category', 'description', 'normal_balance']));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
