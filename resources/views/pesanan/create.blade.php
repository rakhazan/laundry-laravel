@extends('layouts.fullscreen')

@section('contents')
    <h1 class="display-4 font-weight-bold mb-30">Pesanan Baru</h1>

    @if (session('error'))
        <div class="alert alert-danger mb-10">{{ session('error') }}</div>
    @endif

    <div class="card">
        <form action="{{ route(auth()->user()->peran . '.pesanan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">
            <div class="card-body">
                <div class="d-flex align-items-center mb-30 " style="gap: 1rem">
                    <h3 class="font-weight-bold">Form Pesanan</h3> -
                    <p style="margin: 0 0 5px">Layanan ({{ $layanan->kategori }}): <b>{{ $layanan->nama }}</b></p>
                </div>
                <div class="row" style="row-gap: 1rem">
                    <div class="col-12 col-md-6">
                        @php
                            $nama_pelanggan = auth()->user()->peran == 'pelanggan' ? auth()->user()->nama : '';
                        @endphp
                        <div class="d-flex flex-column" style="gap: 1rem">
                            <div class="form-group mb-10">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="single-input"
                                    value="{{ old('nama_pelanggan') ?? $nama_pelanggan }}"
                                    {{ auth()->user()->peran == 'pelanggan' ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group mb-10">
                                <label for="jadwal_pengambilan">Jadwal Pengambilan</label>
                                <input type="datetime-local" name="jadwal_pengambilan" id="jadwal_pengambilan"
                                    class="single-input" value="{{ old('jadwal_pengambilan') }}">
                            </div>
                            <div class="form-group">
                                <label for="jadwal_pengantaran">Jadwal Pengantaran</label>
                                <input type="datetime-local" name="jadwal_pengantaran" id="jadwal_pengantaran"
                                    class="single-input" value="{{ old('jadwal_pengantaran') }}">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="textl" name="alamat" id="alamat" class="single-input"
                                    value="{{ old('alamat') ?? auth()->user()->alamat }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-column" style="gap: 1rem">
                            <div class="form-group mb-10">
                                <label for="harga">
                                    Harga {{ $layanan->kategori == 'kiloan' ? '/ Kg' : '' }}
                                </label>
                                <div class="input-group-icon">
                                    <div class="icon">Rp</div>
                                    <input type="number" name="harga" id="harga" placeholder="Masukkan harga"
                                        class="single-input" value="{{ $layanan->harga }}" readonly>
                                </div>
                            </div>
                            @if (Auth::user()->peran == 'kasir')
                                <div class="form-group">
                                    <label for="jumlah">
                                        Jumlah {{ $layanan->kategori == 'kiloan' ? '(Kg)' : '' }}
                                    </label>
                                    <input type="number" name="jumlah" id="jumlah" placeholder="Masukkan jumlah"
                                        class="single-input" value="{{ old('jumlah') ?? '0' }}">
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="">Metode Pembayaran</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="metode_pembayaran" id="tunai"
                                        value="tunai" checked>
                                    <label for="tunai" class="form-check-label">Tunai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="metode_pembayaran" id="non-tunai"
                                        value="non-tunai">
                                    <label for="non-tunai" class="form-check-label">Non-Tunai</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end mt-10">
                    @if (Auth::user()->peran == 'kasir')
                        <input type="hidden" name="total_biaya" id="total">
                        <h4 class="font-weight-bold text-primary h1 text-right mb-30">
                            Rp<span id="total-display">0</span>
                        </h4>
                    @endif
                    <button type="submit" class="genric-btn primary">Buat Pesanan</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('add-js')
    <script>
        const jumlah = $('#jumlah')
        $(document).ready(() => {
            kalkulasi()
            jumlah.on('change', kalkulasi)
        })

        const kalkulasi = () => {
            const harga = $('#harga').val()
            const totalBiaya = harga * jumlah.val()
            $('#total-display').text(totalBiaya)
            $('#total').val(totalBiaya)
        }
    </script>
@endpush
