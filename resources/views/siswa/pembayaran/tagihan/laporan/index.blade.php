@extends('layouts.backend.app')
@section('title', 'Laporan')
@section('content_title', 'Laporan')
@section('content')
    <x-alert></x-alert>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Laporan Pembayaran</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('siswa.laporan-tagihan.show') }}">
                        @csrf
                        <div class="form-group">
                            <label for="tagihan_id">Tagihan</label>
                            <select name="tagihan_id" required="" class="form-control" id="tagihan_id">
                                <option disabled="" selected="">- PILIH Tagihan -</option>
                                @foreach ($spp as $row)
                                    <option value="{{ $row->tagihan->id }}">{{ $row->tagihan->nama_tagihan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-print fa-fw"></i> CETAK LAPORAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
