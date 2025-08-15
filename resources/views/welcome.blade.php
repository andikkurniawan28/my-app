@extends('template.master')

@section('dashboard-active', 'active')

@section('content')
    <div class="container">
        <h1 class="h3 mb-3"><strong>Dashboard</strong></h1><br>

        <h4 class="mt-1"><strong>Per Bulan</strong></h4>
        <div id="neraca-cards" class="row"></div>
        <div id="neraca-breakdown" class="row"></div>

        <h4 class="mt-1"><strong>Sampai Dengan</strong></h4>
        <div id="neraca-sampai-cards" class="row"></div>
        <div id="neraca-sampai-breakdown" class="row"></div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatID = num => new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(num);

            // ==== Fungsi render card dan table ====
            function renderNeraca(bl, bi, cardsId, breakdownId) {
                document.getElementById(cardsId).innerHTML = `
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-1">Aset</div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tr><th>Bulan Lalu</th><th>Bulan Ini</th></tr>
                                    <tr><td>${formatID(bl.total_asset)}</td><td>${formatID(bi.total_asset)}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white py-1">Kewajiban + Modal Terkoreksi</div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tr><th>Bulan Lalu</th><th>Bulan Ini</th></tr>
                                    <tr><td>${formatID(bl.liability_plus_equity)}</td><td>${formatID(bi.liability_plus_equity)}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning py-1">Ketidakseimbangan Neraca</div>
                        <div class="card-body p-2">
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
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white py-1">Saldo Akun dari Kategori</div>
                        <div class="card-body p-2">
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

            // ==== Fetch untuk per bulan ====
            fetch('{{ route('dashboard.neracaBulanLaluDanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    renderNeraca(data.bulan_lalu, data.bulan_ini, 'neraca-cards', 'neraca-breakdown');
                });

            // ==== Fetch untuk sampai dengan ====
            fetch('{{ route('dashboard.neracaSampaiDenganBulanLaluDanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    renderNeraca(data.bulan_lalu, data.bulan_ini, 'neraca-sampai-cards',
                        'neraca-sampai-breakdown');
                });

            // ==== Fetch pendapatan & beban bulan ini ====
            fetch('{{ route('dashboard.pendapatanBebanBulanIni') }}')
                .then(res => res.json())
                .then(data => {
                    const container = document.createElement('div');
                    container.classList.add('row', 'mt-1');

                    // Card total pendapatan
                    container.innerHTML += `
                    <h4 class="mt-1"><strong>Pendapatan & Beban Bulan Ini</strong></h4>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white py-1">Total Pendapatan Bulan Ini</div>
                                <div class="card-body p-2">
                                    <h5 class="mb-0">${formatID(data.total_pendapatan)}</h5>
                                </div>
                            </div>
                        </div>
                    `;

                    // Card total beban
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white py-1">Total Beban Bulan Ini</div>
                                <div class="card-body p-2">
                                    <h5 class="mb-0">${formatID(data.total_beban)}</h5>
                                </div>
                            </div>
                        </div>
                    `;

                    // Hitung laba rugi
                    let labaRugi = data.total_pendapatan - data.total_beban;

                    // Card laba rugi
                    let warnaLabaRugi = labaRugi >= 0 ? 'bg-success' : 'bg-warning';
                    let labelLabaRugi = labaRugi >= 0 ? 'Keuntungan Bulan Ini' : 'Kerugian Bulan Ini';

                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header ${warnaLabaRugi} text-white py-1">${labelLabaRugi}</div>
                                <div class="card-body p-2">
                                    <h5 class="mb-0">${formatID(labaRugi)}</h5>
                                </div>
                            </div>
                        </div>
                    `;

                    // Table top 5 pendapatan & beban
                    container.innerHTML += `
                        <div class="col-md-6 mt-1">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white py-1">Top 5 Akun Pendapatan</div>
                                <div class="card-body p-2">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead><tr><th>Akun</th><th>Saldo</th></tr></thead>
                                        <tbody>
                                            ${data.top_pendapatan.map(item => `
                                                    <tr><td>${item.account}</td><td>${formatID(item.saldo)}</td></tr>
                                                `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-white py-1">Top 5 Akun Beban</div>
                                <div class="card-body p-2">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead><tr><th>Akun</th><th>Saldo</th></tr></thead>
                                        <tbody>
                                            ${data.top_beban.map(item => `
                                                    <tr><td>${item.account}</td><td>${formatID(item.saldo)}</td></tr>
                                                `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;

                    document.querySelector('.container').appendChild(container);
                });

        });
    </script>
@endsection
