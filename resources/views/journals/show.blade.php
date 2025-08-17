@extends('template.master')

@section('journals-active', 'active')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3"><strong>Detil Jurnal</strong></h1>

        <div class="card mb-3">
            <div class="card-body">
                <p>
                    <strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($journal->date)->locale('id')->translatedFormat('l, d/m/Y') }}
                </p>
                <p><strong>Keterangan:</strong> {{ $journal->description }}</p>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($journal->details as $detail)
                                <tr>
                                    <td>{{ $detail->account->name }}</td>
                                    <td>{{ $detail->debit ? number_format($detail->debit, 0, ',', '.') : '-' }}</td>
                                    <td>{{ $detail->credit ? number_format($detail->credit, 0, ',', '.') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>Total</th>
                            <th>{{ number_format($journal->debit, 0, ',', '.') }}</th>
                            <th>{{ number_format($journal->credit, 0, ',', '.') }}</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <a href="{{ route('journals.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
