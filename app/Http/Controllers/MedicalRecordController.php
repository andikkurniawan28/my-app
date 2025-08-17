<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Yajra\DataTables\DataTables;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MedicalRecord::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    $carbon = Carbon::parse($row->date)->locale('id');
                    $row->date = $carbon->translatedFormat('l, d/m/Y');
                    return $row->date;
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('medicalRecords.show', $row->id);
                    $editUrl = route('medicalRecords.edit', $row->id);
                    $deleteUrl = route('medicalRecords.destroy', $row->id);

                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $showUrl . '" class="btn btn-sm btn-info">Detail</a>
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

        return view('medicalRecords.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicalRecords.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('medicalRecords.index')->with('success', 'Data rekam medis berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        return view('medicalRecords.edit', compact('medicalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        return view('medicalRecords.show', compact('medicalRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('medicalRecords.index')->with('success', 'Data rekam medis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return redirect()->route('medicalRecords.index')->with('success', 'Data rekam medis berhasil dihapus.');
    }
}
