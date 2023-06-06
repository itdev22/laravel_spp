@extends('layouts.backend.app')
@section('title', 'Pembayaran Spp ' . $siswa->nama_siswa)
@section('content_title', 'Pembayaran Parmas ' . $siswa->nama_siswa)
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="callout callout-success">
                <h5>Info Siswa:</h5>

                <p>
                    Nama Siswa : <b>{{ $siswa->nama_siswa }}</b><br>
                    Nisn : <b>{{ $siswa->nisn }}</b><br>
                    Kelas : <b>{{ $siswa->kelas->nama_kelas }}</b>
                </p>
            </div>
            {{-- <div class="callout callout-danger">
                <h5>Pemberitahuan!</h5>

                <p>Garis biru pada list tahun menandakan tahun aktif / tahun sekarang.</p>
            </div> --}}
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    {{-- <a href="javascript:void(0)" class="btn btn-primary btn-sm">
                        <i class="fas fa-circle fa-fw"></i> PILIH TAHUN
                    </a> --}}
                    <a href="{{ route('tagihan.pembayaran.status-pembayaran') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-window-close fa-fw"></i> BACK TO LIST
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach ($spp as $row)
                            {{-- @if ($row->tahun == date('Y'))
                                <a href="{{ route('tagihan.pembayaran.status-pembayaran.show-status', [$siswa->nisn, $row->tahun]) }}"
                                    class="list-group-item list-group-item-action active">
                                    {{ $row->tahun }}
                                </a>
                            @else
                                <a href="{{ route('tagihan.pembayaran.status-pembayaran.show-status', [$siswa->nisn, $row->tahun]) }}"
                                    class="list-group-item list-group-item-action">
                                    {{ $row->tahun }}
                                </a>
                            @endif --}}
                            <a href="{{ route('tagihan.pembayaran.bayar', $row->siswa->nisn) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="">{{ $row->tagihan->nama_tagihan }} </h5>
                                @if ($row->status == 'lunas')
                                    <p class="list-group-item list-group-item-action bg-success m-auto">{{ $row->status }}
                                    </p>
                                @elseif ($row->status == 'dicicil')
                                    <p class="list-group-item list-group-item-action bg-warning m-auto">{{ $row->status }}
                                    </p>
                                @else
                                    <p class="list-group-item list-group-item-action bg-success m-auto">{{ $row->status }}
                                    </p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
