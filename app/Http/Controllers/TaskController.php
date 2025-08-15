<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Task::with('project');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('project', function ($row) {
                    return $row->project ? $row->project->title : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('tasks.edit', $row->id);
                    $deleteUrl = route('tasks.destroy', $row->id);
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

        return view('tasks.index');
    }

    public function create()
    {
        $projects = Project::all();
        $types = ['harian', 'project', 'ad_hoc'];
        $statuses = ['belum_dimulai', 'sedang_berjalan', 'selesai'];

        return view('tasks.create', compact('projects', 'types', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'required|in:harian,project,ad_hoc',
            'status' => 'required|in:belum_dimulai,sedang_berjalan,selesai',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $types = ['harian', 'project', 'ad_hoc'];
        $statuses = ['belum_dimulai', 'sedang_berjalan', 'selesai'];

        return view('tasks.edit', compact('task', 'projects', 'types', 'statuses'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'required|in:harian,project,ad_hoc',
            'status' => 'required|in:belum_dimulai,sedang_berjalan,selesai',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
