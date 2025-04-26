@extends('dashboard.layouts.main')

@section('isi')
<div class="row">
    <!-- Penghasilan Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Penghasilan (Hari Ini)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp{{ number_format($penghasilanHariIni, 0, ',', '.') }}
                    </div>
                </div>
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- Penghasilan Bulan Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Penghasilan (Bulan Ini)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp{{ number_format($penghasilanBulanIni, 0, ',', '.') }}
                    </div>
                </div>
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- Total Pelanggan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Total Pelanggan
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($totalPelanggan) }}
                    </div>
                </div>
                <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Total Transaksi Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Total Transaksi (Hari Ini)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($totalTransaksiHariIni) }}
                    </div>
                </div>
                <i class="fas fa-receipt fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- Total Produk Terjual Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Produk Terjual (Hari Ini)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($totalProdukTerjualHariIni) }}
                    </div>
                </div>
                <i class="fas fa-boxes fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris Bulan Ini -->
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Produk Terlaris (Bulan Ini)
                    </div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        @if($namaProdukTerlaris)
                            {{ $namaProdukTerlaris }}
                            @if($jumlahProdukTerlaris)
                                ({{ number_format($jumlahProdukTerlaris) }} Terjual)
                            @endif
                        @else
                            Belum ada transaksi
                        @endif
                    </div>
                </div>
                <i class="fas fa-star fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
@endsection
