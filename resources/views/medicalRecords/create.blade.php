@extends('template.master')

@section('medicalRecords-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Tambah Rekam Medis</strong></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('medicalRecords.store') }}" method="POST">
                @csrf

                @include('medicalRecords.form')

                <div class="d-flex justify-content-between">
                    <a href="{{ route('medicalRecords.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
