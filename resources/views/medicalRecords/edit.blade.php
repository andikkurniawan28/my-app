@extends('template.master')

@section('medicalRecords-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Edit Rekam Medis</strong></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('medicalRecords.update', $medicalRecord->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('medicalRecords.form', ['medicalRecord' => $medicalRecord])

                <div class="d-flex justify-content-between">
                    <a href="{{ route('medicalRecords.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
