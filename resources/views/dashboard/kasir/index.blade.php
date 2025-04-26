@extends('dashboard.layouts.main')

@section('isi')
    <form id="form-kasir" action="{{ route('kasir.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Pilih Pelanggan Berdasarkan Nomor Telepon --}}
        <div class="mb-3">
            <label for="no_telp" class="form-label">Pilih Nomor Telepon Pelanggan</label>
            <select class="form-select" name="no_telp" id="no_telp">
                <option value="">-- Pilih Nomor Telepon --</option>
                @foreach ($pelanggans as $p)
                <option value="{{ $p->no_telp }}" data-nama="{!! $p->nama !!}">{{ $p->no_telp }}</option>
                @endforeach
            </select>
        </div>


        {{-- Form Input Nama Pelanggan --}}
        <div class="mb-3">
            <label for="nama_pelanggan" class="form-label">Nama Pelanggan (Opsional)</label>
            <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan"
                placeholder="Masukkan Nama Pelanggan">
        </div>

        <script>
             // 
             document.addEventListener('DOMContentLoaded', function() {
                const selectTelp = document.getElementById('no_telp');
                const inputNama = document.getElementById('nama_pelanggan');

                selectTelp.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const nama = selectedOption.getAttribute('data-nama');

                    if (nama) {
                        inputNama.value = nama;
                        inputNama.readOnly = true;
                    } else {
                        inputNama.value = '';
                        inputNama.readOnly = false;
                    }
                });
            });
        </script>

        <hr>

        {{-- Bagian Produk --}}
        <div id="produk-list">
            <div class="produk-item row g-2 mb-3">
                <div class="col-md-5">
                    <label for="produk_id" class="form-label">Pilih Produk</label>
                    <select name="produk_id[]" class="form-select produk-select" required>
                        <option selected disabled>-- Pilih Produk --</option>
                        @foreach ($products as $produk)
                            <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}">
                                {{ $produk->nama_product }} (Stok: {{ $produk->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Subtotal</label>
                    <input type="text" name="subtotal[]" class="form-control subtotal-input" readonly>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-produk"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" id="tambah-produk" class="btn btn-secondary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </button>
        </div>

        <hr>

        {{-- Total Harga & Diskon --}}
        <div class="mb-3">
            <label class="form-label">Total Harga</label>
            <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal Bayar</label>
            <input type="number" name="bayar" id="bayar" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kembalian</label>
            <input type="text" id="kembalian" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Transaksi
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noTelpSelect = document.getElementById('no_telp');
            const namaInput = document.getElementById('nama_pelanggan');

            if (noTelpSelect && namaInput) {
                noTelpSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];

                    // Cek apakah opsi valid (bukan placeholder)
                    if (this.selectedIndex === 0 || !selectedOption.hasAttribute('data-nama')) {
                        namaInput.value = '';
                        namaInput.removeAttribute('readonly');
                        return;
                    }

                    const nama = selectedOption.getAttribute('data-nama');
                    namaInput.value = nama;
                    namaInput.setAttribute('readonly', true);
                });
            }

            // ====================
            // Fungsi Lain (tetap seperti sebelumnya, hanya pastikan form id disesuaikan jika perlu)
            // ====================
            const tambahBtn = document.getElementById('tambah-produk');
            const produkList = document.getElementById('produk-list');

            tambahBtn.addEventListener('click', function() {
                const itemPertama = produkList.querySelector('.produk-item');
                const duplikat = itemPertama.cloneNode(true);

                duplikat.querySelectorAll('input').forEach(input => input.value = '');
                duplikat.querySelector('select').selectedIndex = 0;

                produkList.appendChild(duplikat);
            });

            produkList.addEventListener('click', function(e) {
                if (e.target.closest('.remove-produk')) {
                    const allItems = produkList.querySelectorAll('.produk-item');
                    if (allItems.length > 1) {
                        e.target.closest('.produk-item').remove();
                    }
                }
            });

            produkList.addEventListener('input', function(e) {
                const row = e.target.closest('.produk-item');
                const select = row.querySelector('.produk-select');
                const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
                const harga = parseInt(select.selectedOptions[0]?.dataset.harga) || 0;
                const subtotal = jumlah * harga;
                row.querySelector('.subtotal-input').value = subtotal;
                hitungTotal();
            });

            function hitungTotal() {
                let total = 0;
                document.querySelectorAll('.subtotal-input').forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                document.getElementById('total_harga').value = total;
                hitungKembalian();
            }

            document.getElementById('bayar').addEventListener('input', hitungKembalian);

            function hitungKembalian() {
                const total = parseInt(document.getElementById('total_harga').value) || 0;
                const bayar = parseInt(document.getElementById('bayar').value) || 0;
                const kembali = bayar - total;
                document.getElementById('kembalian').value = kembali >= 0 ? kembali : 0;
            }

            document.getElementById('form-kasir').addEventListener('submit', function(e) {
                const total = parseInt(document.getElementById('total_harga').value) || 0;
                const bayar = parseInt(document.getElementById('bayar').value) || 0;
                if (bayar < total) {
                    e.preventDefault();
                    alert('Nominal bayar tidak cukup untuk membayar total harga!');
                }
            });

        });
    </script>




@endsection
