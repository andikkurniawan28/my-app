@extends('template.master')

@section('dashboard-active', 'active')

@section('content')
    <div class="container-fluid py-0 px-0">
        <h1 class="h3 mb-4"><strong>Dashboard</strong></h1>

        <!-- Quick Actions -->
        <h4 class="mt-4"><strong>Quick Actions</strong></h4>
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <a href="{{ route('journals.create') }}" class="btn btn-primary w-100 shadow-sm mb-2">
                    <i class="bi bi-journal-text"></i> Catat Jurnal
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('schedules.create') }}" class="btn btn-danger w-100 shadow-sm mb-2">
                    <i class="bi bi-calendar"></i> Catat Jadwal
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('projects.create') }}" class="btn btn-success w-100 shadow-sm mb-2">
                    <i class="bi bi-kanban"></i> Buat Proyek
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('tasks.create') }}" class="btn btn-dark w-100 shadow-sm mb-2">
                    <i class="bi bi-list-task"></i> Buat Tugas
                </a>
            </div>
        </div>

        <!-- Jadwal, Proyek & Tugas -->
        <h4 class="mt-4"><strong>To Do List</strong></h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white py-2">Jadwal Mendatang</div>
                    <div class="card-body p-3" id="jadwal-menunggu">
                        <p class="text-muted">Memuat jadwal...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-warning text-white py-2">Proyek Belum Selesai</div>
                    <div class="card-body p-3" id="proyek-belum-dimulai">
                        <p class="text-muted">Memuat proyek...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-secondary text-white py-2">Tugas Belum Selesai</div>
                    <div class="card-body p-3" id="tugas-belum-selesai">
                        <p class="text-muted">Memuat tugas...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keuangan Per Bulan -->
        <h4 class="mt-4"><strong>Keuangan Per Bulan</strong></h4>
        <div class="row" id="neraca-cards"></div>
        <div class="row" id="neraca-breakdown"></div>

        <!-- Keuangan Sampai Dengan -->
        <h4 class="mt-4"><strong>Keuangan Sampai Dengan</strong></h4>
        <div class="row" id="neraca-sampai-cards"></div>
        <div class="row" id="neraca-sampai-breakdown"></div>

        <!-- Pendapatan & Beban Bulan Ini -->
        <h4 class="mt-4"><strong>Pendapatan & Beban Bulan Ini</strong></h4>
        <div class="row mt-4" id="pendapatan-beban"></div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const formatID = num => new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(num);

            // ==== Fungsi render neraca ====
            function renderNeraca(bl, bi, cardsId, breakdownId) {
                document.getElementById(cardsId).innerHTML = `
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white py-2">Aset</div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tr><th>Bulan Lalu</th><th>Bulan Ini</th></tr>
                                        <tr><td>${formatID(bl.total_asset)}</td><td>${formatID(bi.total_asset)}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white py-2">Kewajiban + Modal Terkoreksi</div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tr><th>Bulan Lalu</th><th>Bulan Ini</th></tr>
                                        <tr><td>${formatID(bl.liability_plus_equity)}</td><td>${formatID(bi.liability_plus_equity)}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white py-2">Ketidakseimbangan Neraca</div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tr><th>Bulan Lalu</th><th>Bulan Ini</th></tr>
                                        <tr>
                                            <td class="${bl.imbalance == 0 ? 'text-success' : 'text-danger'}">${formatID(bl.imbalance)}</td>
                                            <td class="${bi.imbalance == 0 ? 'text-success' : 'text-danger'}">${formatID(bi.imbalance)}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById(breakdownId).innerHTML = `
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white py-2">Saldo Akun dari Kategori</div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Bulan Lalu</th>
                                                <th>Bulan Ini</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>Kewajiban</td><td>${formatID(bl.total_liability)}</td><td>${formatID(bi.total_liability)}</td></tr>
                                            <tr><td>Modal</td><td>${formatID(bl.total_equity)}</td><td>${formatID(bi.total_equity)}</td></tr>
                                            <tr><td>Pendapatan</td><td>${formatID(bl.total_revenue)}</td><td>${formatID(bi.total_revenue)}</td></tr>
                                            <tr><td>Beban</td><td>${formatID(bl.total_expense)}</td><td>${formatID(bi.total_expense)}</td></tr>
                                            <tr><td>Modal Terkoreksi</td><td>${formatID(bl.equity_adjusted)}</td><td>${formatID(bi.equity_adjusted)}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // ==== Fetch Jadwal Menunggu ====
            fetch('{{ route('dashboard.jadwalMenunggu') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('jadwal-menunggu');
                    if (data.length === 0) {
                        container.innerHTML = '<p>Tidak ada jadwal menunggu.</p>';
                        return;
                    }

                    // Sort by date ascending
                    data.sort((a, b) => new Date(a.date) - new Date(b.date));

                    container.innerHTML = `
                        <ul class="timeline list-unstyled mb-0">
                            ${data.map(j => `
                                <li class="mb-3 position-relative ps-4">
                                    <span class="position-absolute top-0 start-0 translate-middle rounded-circle bg-info"
                                        style="width: 10px; height: 10px;"></span>
                                    <div class="fw-bold">${j.title}</div>
                                    <div class="text-muted">${j.date}</div>
                                    <div class="small text-secondary">
                                        ${j.start_time && j.finish_time
                                            ? `${j.start_time} - ${j.finish_time}`
                                            : j.start_time
                                                ? j.start_time
                                                : j.finish_time
                                                    ? j.finish_time
                                                    : '-'}
                                    </div>
                                </li>
                            `).join('')}
                        </ul>`;

            });

            // ==== Fetch Proyek Belum Dimulai ====
            fetch('{{ route('dashboard.proyekBelumDimulai') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('proyek-belum-dimulai');
                    if (data.length === 0) {
                        container.innerHTML = '<p>Tidak ada proyek belum dimulai.</p>';
                        return;
                    }

                    container.innerHTML = `
                    <ul class="list-group list-group-flush">
                        ${data.map(p => `
                                        <li class="list-group-item">
                                            <div class="fw-bold">${p.title}</div>
                                            <div><strong>Deadline:</strong> ${p.deadline}</div>
                                            <div><strong>Deskripsi:</strong> ${p.description}</div>
                                        </li>
                                    `).join('')}
                    </ul>
                `;
            });

            // ==== Fetch Tugas Belum Selesai ====
            fetch('{{ route('dashboard.tugasBelumSelesai') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('tugas-belum-selesai');
                    if (data.length === 0) {
                        container.innerHTML = '<p>Tidak ada tugas belum selesai.</p>';
                        return;
                    }
                    container.innerHTML = `
                        <ul class="list-group list-group-flush">
                            ${data.map(t => `
                                <li class="list-group-item d-flex align-items-center">
                                    <form method="POST" action="{{ route('dashboard.selesaikanTugas') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="${t.id}">
                                        <input type="hidden" name="selesai" value="1">
                                        <input type="checkbox" class="form-check-input me-2"
                                            onchange="this.form.submit()" />
                                    </form>
                                    <div>
                                        <div class="fw-bold">${t.title}</div>
                                        <div>${t.description ?? '-'}</div>
                                    </div>
                                </li>
                            `).join('')}
                        </ul>
                    `;
            });

            // ==== Fetch Neraca Bulan Ini ====
            fetch('{{ route('dashboard.neracaBulanLaluDanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    renderNeraca(data.bulan_lalu, data.bulan_ini, 'neraca-cards', 'neraca-breakdown');
            });

            // ==== Fetch Neraca Sampai Dengan ====
            fetch('{{ route('dashboard.neracaSampaiDenganBulanLaluDanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    renderNeraca(data.bulan_lalu, data.bulan_ini, 'neraca-sampai-cards',
                        'neraca-sampai-breakdown');
            });

            // ==== Fetch Pendapatan & Beban Bulan Ini ====
            fetch('{{ route('dashboard.pendapatanBebanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('pendapatan-beban');

                    // Fungsi untuk render top 5 akun
                    const renderTop = (items) => {
                        return `<ol class="ps-3 mb-0">
                            ${items.map(i => `<li>${i.account}: ${formatID(i.saldo)}</li>`).join('')}
                        </ol>`;
                    };

                    container.innerHTML = `
                        <!-- Total Pendapatan -->
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white py-2">Total Pendapatan Bulan Ini</div>
                                <div class="card-body p-3">
                                    <h5 class="mb-0">${formatID(data.total_pendapatan)}</h5>
                                    <small class="text-muted">Pendapatan Tertinggi:</small>
                                    ${renderTop(data.top_pendapatan)}
                                </div>
                            </div>
                        </div>

                        <!-- Total Beban -->
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white py-2">Total Beban Bulan Ini</div>
                                <div class="card-body p-3">
                                    <h5 class="mb-0">${formatID(data.total_beban)}</h5>
                                    <small class="text-muted">Beban Tertinggi:</small>
                                    ${renderTop(data.top_beban)}
                                </div>
                            </div>
                        </div>

                        <!-- Keuntungan / Kerugian -->
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-header ${data.total_pendapatan - data.total_beban >= 0 ? 'bg-success' : 'bg-warning'} text-white py-2">
                                    ${data.total_pendapatan - data.total_beban >= 0 ? 'Keuntungan Bulan Ini' : 'Kerugian Bulan Ini'}
                                </div>
                                <div class="card-body p-3">
                                    <h5 class="mb-0">${formatID(data.total_pendapatan - data.total_beban)}</h5>
                                </div>
                            </div>
                        </div>
                    `;
            });

        });
    </script>
@endsection
