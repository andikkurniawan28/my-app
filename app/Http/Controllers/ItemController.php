<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('items.edit', $row->id);
                    $deleteUrl = route('items.destroy', $row->id);
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                            <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('items.index');
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'last_location' => 'required|string',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'last_location' => 'required|string',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}
