@extends('layouts.backend.app')
@section('title', 'Laporan Parmas')
@section('content_title', 'Laporan Parmas')
@section('content')
    <x-alert></x-alert>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Laporan Pembayaran</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('parmas.pembayaran.print-pdf') }}">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" required="" class="form-control"
                                id="tanggal_mulai">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" required="" class="form-control"
                                id="tanggal_selesai">
                        </div>
                        <div class="row align-items-start">
                            <div class="m-2">
                                <label for="kelas">Kelas</label>
                                <select name="kelas" id="kelas" class="form-control">
                                    @foreach ($kelass as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-2">
                                <label for="tahun">Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="m-2">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    @foreach (Universe::bulanAll() as $bulan)
                                        <option value="{{ $bulan['nama_bulan'] }}">{{ $bulan['nama_bulan'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-print fa-fw"></i> PRINT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on("click", "#preview", function() {
            var tanggal_mulai = $("#tanggal_mulai").val()
            var tanggal_selesai = $("#tanggal_selesai").val()

            $.ajax({
                url: "/pembayaran/laporan/preview-pdf",
                method: "GET",
                data: {
                    tanggal_mulai: tanggal_mulai,
                    tanggal_selesai: tanggal_selesai,
                },
                success: function() {
                    window.open('/pembayaran/laporan/preview-pdf')
                }
            })
        })
    </script>
@endpush
