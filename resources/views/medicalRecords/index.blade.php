@extends('template.master')

@section('medicalRecords-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Daftar Rekam Medis</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('medicalRecords.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="medicalRecordTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Tekanan Darah</th>
                            <th>Suhu (°C)</th>
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
    $('#medicalRecordTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('medicalRecords.index') }}",
        order: [[0, 'desc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'date', name: 'date' },
            { data: 'weight', name: 'weight' },
            { data: 'height', name: 'height' },
            {
                data: null,
                render: function(data) {
                    return data.blood_pressure_systolic + '/' + data.blood_pressure_diastolic;
                },
                name: 'blood_pressure'
            },
            { data: 'temperature', name: 'temperature' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
