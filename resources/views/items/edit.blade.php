@extends('template.master')

@section('items-active', 'active')

@section('content')
<div class="container-fluid py-0 px-0">
    <h1 class="h3 mb-3"><strong>Edit Barang Penting</strong></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Barang</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $item->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="last_location" class="form-label">Lokasi Terakhir</label>
                    <textarea name="last_location" id="last_location" rows="3"
                        class="form-control @error('last_location') is-invalid @enderror"
                        required>{{ old('last_location', $item->last_location) }}</textarea>
                    @error('last_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
