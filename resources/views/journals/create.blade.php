@extends('template.master')

@section('journals-active', 'active')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3"><strong>Tambah Jurnal</strong></h1>

        <form action="{{ route('journals.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Akun</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="journalDetails">
                    <tr>
                        <td>
                            <select name="account_id[]" class="form-control" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" step="0.01" name="debit[]" class="form-control form-control-sm"></td>
                        <td><input type="number" step="0.01" name="credit[]" class="form-control form-control-sm"></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="addRow" class="btn btn-secondary btn-sm">Tambah Baris</button>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script>
        document.getElementById('addRow').addEventListener('click', function() {
            let row = document.querySelector('#journalDetails tr').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            document.getElementById('journalDetails').appendChild(row);
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
