@extends('template.master')

@section('schedules-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-fluid py-0 px-0">

        <h1 class="h3 mb-3"><strong>Daftar Jadwal</strong></h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="scheduleTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('#scheduleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('schedules.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'date', name: 'date' },
                    { data: 'title', name: 'title' },
                    { data: 'description', name: 'description' },
                    { data: 'start_time', name: 'start_time' },
                    { data: 'finish_time', name: 'finish_time' },
                    { data: 'status', name: 'status' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
