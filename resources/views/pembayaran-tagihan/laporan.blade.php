@extends('layouts.backend.app')
@section('title', 'Laporan Tagihan')
@section('content_title', 'Laporan Tagihan')
@section('content')
    <x-alert></x-alert>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Laporan Pembayaran</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tagihan.pembayaran.print-pdf') }}">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" id="tanggal_mulai">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" id="tanggal_selesai">
                        </div>
                        <div class="row align-items-start">
                            <div class="m-2">
                                <label for="kelas">Kelas</label>
                                <select name="kelas" id="kelas" class="form-control">
                                    <option value=""></option>
                                    @foreach ($kelass as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-2">
                                <label for="nama_tagihan">Nama Tagihan</label>
                                <select name="nama_tagihan" id="nama_tagihan" class="form-control">
                                    <option value=""></option>
                                    @foreach ($tagihans as $tagihan)
                                        <option value="{{ $tagihan->id }}">{{ $tagihan->nama_tagihan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-2">
                                <label for="status_tagihan">Status Tagihan</label>
                                <select name="status_tagihan" id="status_tagihan" class="form-control">
                                    <option value=""></option>
                                    <option value="lunas">Lunas</option>
                                    <option value="belum lunas">Belum Lunas</option>
                                    <option value="dicicil">Dicicil</option>
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
