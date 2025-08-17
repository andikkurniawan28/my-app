<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Project::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('deadline', function ($row) {
                    $carbon = Carbon::parse($row->deadline)->locale('id');
                    $row->deadline = $carbon->translatedFormat('l, d/m/Y');
                    return $row->deadline;
                })
                ->editColumn('status', function ($row) {
                    return ucfirst(str_replace('_', ' ', $row->status));
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('projects.edit', $row->id);
                    $deleteUrl = route('projects.destroy', $row->id);
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

        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ['belum_dimulai', 'sedang_berjalan', 'selesai', 'dibatalkan'];

        return view('projects.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            // 'status' => 'required|in:belum_dimulai,sedang_berjalan,selesai,dibatalkan',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $statuses = ['belum_dimulai', 'sedang_berjalan', 'selesai', 'dibatalkan'];

        return view('projects.edit', compact('project', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'status' => 'required|in:belum_dimulai,sedang_berjalan,selesai,dibatalkan',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
