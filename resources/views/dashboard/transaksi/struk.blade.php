<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 250px;
            margin: 0 auto;
        }
        .container {
            text-align: center;
            padding: 5px;
        }
        .header {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sub-header {
            font-size: 10px;
            margin-top: 2px;
        }
        .details, .footer {
            margin-top: 10px;
            text-align: left;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .total, .payment-info {
            margin-top: 10px;
            font-weight: bold;
        }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .footer {
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Toko Cigo</div>
        <div class="sub-header">
            {{ $penjualan->tanggal_penjualan->format('d/m/Y H:i') }}<br>
            No. Transaksi: {{ $penjualan->nomor_transaksi }}
        </div>

        <hr>

        <div class="details">
            <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? $penjualan->nama_pelanggan_manual ?? '-' }}</p>
            <p><strong>No Telp:</strong> {{ $penjualan->pelanggan->no_telp ?? $penjualan->no_telp_manual ?? '-' }}</p>
        </div>

        <hr>

        @foreach ($penjualan->detail as $detail)
            <div class="item">
                <span>{{ $detail->produk->nama_product ?? 'N/A' }} x{{ $detail->jumlah_produk }}</span>
                <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach

        <hr>

        @php
            $totalSebelumDiskon = $penjualan->detail->sum('subtotal');
            $diskon = $penjualan->pelanggan ? round($totalSebelumDiskon * 0.01) : 0;
        @endphp

        @if ($diskon > 0)
            <div class="item">
                <span>Diskon Member (1%)</span>
                <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
            </div>
            <hr>
        @endif

        <div class="total">
            <div class="item">
                <span>Total</span>
                <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="item">
                <span>Bayar</span>
                <span>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="item">
                <span>Kembalian</span>
                <span>Rp {{ number_format($penjualan->bayar - $penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <hr>

        <div class="footer">
            <p>*** TERIMA KASIH ***</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
        </div>
    </div>
</body>
</html>
