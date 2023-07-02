@extends('layouts.backend.app')
@section('title', 'Pembayaran Parmas')
@section('content_title', 'Pembayaran Parmas')
@section('content')
    <div class="row">
        <div class="col-lg-6">

            <div class="card">
                <div class="card-header">Pilih Tahun</div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach ($spp as $row)
                            @if ($row->tahun == date('Y'))
                                <a href="{{ route('siswa.pembayaran-permas.show', $row->tahun) }}"
                                    class="list-group-item list-group-item-action active">
                                    {{ $row->tahun }}
                                </a>
                            @else
                                <a href="{{ route('siswa.pembayaran-permas.show', $row->tahun) }}"
                                    class="list-group-item list-group-item-action">
                                    {{ $row->tahun }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="callout callout-danger">
                <h5>Pemberitahuan!</h5>
                <p>Garis biru pada list tahun menandakan tahun aktif / tahun sekarang.</p>
            </div>
        </div>
        <div class="card-body">

            <a href="{{ route('siswa.pembayaran-permas.bayar', $user->nisn) }}" class="btn btn-primary">Bayar</a>
        </div>
    </div>
@endsection
