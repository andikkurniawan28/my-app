@extends('template.master')

@section('accounts-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-fluid py-0 px-0">

        <h1 class="h3 mb-3"><strong>Daftar Akun</strong></h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('accounts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="accountTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                {{-- <th>Category</th> --}}
                                <th>Nama</th>
                                <th>Saldo</th>
                                {{-- <th>Description</th>
                                <th>NB</th> --}}
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
            $('#accountTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('accounts.index') }}",
                order: [
                    [0, 'asc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    // {
                    //     data: 'category',
                    //     name: 'category'
                    // },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo'
                    },
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    // {
                    //     data: 'normal_balance',
                    //     name: 'normal_balance'
                    // },
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
