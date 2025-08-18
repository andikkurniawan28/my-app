<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Schedule::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    $carbon = Carbon::parse($row->date)->locale('id');
                    $row->date = $carbon->translatedFormat('l, d/m/Y');
                    return $row->date;
                })
                ->editColumn('start_time', function ($row) {
                    return Carbon::parse($row->start_time)->format('H:i');
                })
                ->editColumn('finish_time', function ($row) {
                    return Carbon::parse($row->finish_time)->format('H:i');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('schedules.edit', $row->id);
                    $deleteUrl = route('schedules.destroy', $row->id);
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

        return view('schedules.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'];

        return view('schedules.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required',
            'finish_time' => 'required',
            // 'status' => 'required|in:menunggu,berlangsung,selesai,dibatalkan',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $statuses = ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'];

        return view('schedules.edit', compact('schedule', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required',
            'finish_time' => 'required',
            'status' => 'required|in:menunggu,berlangsung,selesai,dibatalkan',
        ]);

        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
