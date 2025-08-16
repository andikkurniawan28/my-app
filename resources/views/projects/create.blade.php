@extends('template.master')

@section('projects-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Tambah Proyek</strong></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" name="deadline" id="deadline"
                           class="form-control @error('deadline') is-invalid @enderror"
                           value="{{ old('deadline') }}" required>
                    @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div> --}}

                <div class="d-flex justify-content-between">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
