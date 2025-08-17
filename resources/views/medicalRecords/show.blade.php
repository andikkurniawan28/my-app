@extends('template.master')

@section('medicalRecords-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Detail Rekam Medis</strong></h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Tanggal</strong></label>
                    <p>
                        {{ \Carbon\Carbon::parse($medicalRecord->date)->locale('id')->translatedFormat('l, d/m/Y') }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Catatan</strong></label>
                    <p>{{ $medicalRecord->notes ?? '-' }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mt-3"><strong>Pengukuran Dasar</strong></h5>
            <ul>
                <li>Berat: {{ $medicalRecord->weight ?? '-' }} kg</li>
                <li>Tinggi: {{ $medicalRecord->height ?? '-' }} cm</li>
                <li>Tekanan Darah:
                    {{ $medicalRecord->blood_pressure_systolic ?? '-' }}/{{ $medicalRecord->blood_pressure_diastolic ?? '-' }} mmHg
                </li>
                <li>Suhu: {{ $medicalRecord->temperature ?? '-' }} °C</li>
                <li>Nadi: {{ $medicalRecord->pulse ?? '-' }} bpm</li>
            </ul>

            <h5 class="mt-3"><strong>Gula Darah</strong></h5>
            <ul>
                <li>Puasa: {{ $medicalRecord->blood_sugar_fasting ?? '-' }} mg/dL</li>
                <li>Sewaktu: {{ $medicalRecord->blood_sugar_random ?? '-' }} mg/dL</li>
                <li>HbA1c: {{ $medicalRecord->hba1c ?? '-' }} %</li>
            </ul>

            <h5 class="mt-3"><strong>Profil Lipid</strong></h5>
            <ul>
                <li>Total Kolesterol: {{ $medicalRecord->cholesterol_total ?? '-' }} mg/dL</li>
                <li>HDL: {{ $medicalRecord->cholesterol_hdl ?? '-' }} mg/dL</li>
                <li>LDL: {{ $medicalRecord->cholesterol_ldl ?? '-' }} mg/dL</li>
                <li>Trigliserida: {{ $medicalRecord->triglycerides ?? '-' }} mg/dL</li>
            </ul>

            <h5 class="mt-3"><strong>Fungsi Ginjal</strong></h5>
            <ul>
                <li>Kreatinin: {{ $medicalRecord->creatinine ?? '-' }} mg/dL</li>
                <li>BUN: {{ $medicalRecord->bun ?? '-' }}</li>
                <li>eGFR: {{ $medicalRecord->egfr ?? '-' }}</li>
            </ul>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('medicalRecords.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('medicalRecords.edit', $medicalRecord->id) }}" class="btn btn-primary">Edit</a>
            </div>

        </div>
    </div>
</div>
@endsection
