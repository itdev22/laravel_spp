@extends('layouts.backend.app')
@section('title', 'Data Pembayaran')
@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('content_title', 'Tambah Pembayaran')
@section('content')
    <x-alert></x-alert>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('tagihan.pembayaran.index') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-window-close fa-fw"></i>
                        BATALKAN
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tagihan.pembayaran.proses-bayar', $siswa->nisn) }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="nama_siswa">Nama Siswa:</label>
                                    <input required="" type="hidden" name="siswa_id" value="{{ $siswa->id }}"
                                        readonly id="siswa_id" class="form-control">
                                    <input required="" type="text" name="nama_siswa" value="{{ $siswa->nama_siswa }}"
                                        readonly id="nama_siswa" class="form-control">
                                    @error('nama_siswa')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="nisn">Nisn:</label>
                                    <input required="" type="text" name="nisn" value="{{ $siswa->nisn }}" readonly
                                        id="nisn" class="form-control">
                                    @error('nisn')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="nis">Nis:</label>
                                    <input required="" type="text" name="nis" value="{{ $siswa->nis }}" readonly
                                        id="nis" class="form-control">
                                    @error('nis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="kelas">Kelas:</label>
                                    <input required="" type="text" name="kelas"
                                        value="{{ $siswa->kelas->nama_kelas }}" readonly id="kelas"
                                        class="form-control">
                                    @error('kelas')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="tagihansiswa_id">Untuk Tagihan:</label>
                                    <select required="" name="tagihansiswa_id" id="tagihansiswa_id"
                                        class="form-control select2bs4">
                                        <option disabled="" selected="">- PILIH TAGIHAN -</option>
                                        @foreach ($tagihansiswas as $tagihansiswa)
                                            <option value="{{ $tagihansiswa->id }}">
                                                {{ $tagihansiswa->tagihan->nama_tagihan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="jumlah_bayar" id="nominal_spp_label">Nominal Tagihan:</label>
                                    <input type="" name="nominal" readonly="" id="nominal" class="form-control">
                                    <input required="" type="hidden" name="jumlah_bayar" readonly=""
                                        id="jumlah_bayar" class="form-control">
                                    @error('jumlah_bayar')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group select2-purple">
                                    <label for="dibayar">Dibayar:</label>
                                    <input required="" type="number" name="dibayar" id="dibayar" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="total_kekurangan">Total Kekurangan:</label>
                                    <input required="" type="" name="total_kekurangan" readonly=""
                                        id="total_kekurangan" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="type_pembayaran">Type Pembayaran:</label>
                                    <input required="" type="" name="metode_pembayaran" readonly=""
                                        id="metode_pembayaran" class="form-control" value="offline">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save fa-fw"></i>
                                KONFIRMASI
                            </button>
                        </div>
                    </form>
                    <table id="dataTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah Bayar</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@push('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js">
    </script>
    <script
        src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js">
    </script>
    <!-- Select2 -->
    <script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        function rupiah(number) {
            var formatter = new Intl.NumberFormat('ID', {
                style: 'currency',
                currency: 'idr',
            })

            return formatter.format(number)
        }

        $(document).on("change", "#tagihansiswa_id", function() {
            // $("#dataTable2").DataTable().ajax.reload(null, false);
            var id = $(this).val()
            var idSiswa = $("#siswa_id").val()
            var nisn = $("#nisn").val()

            $.ajax({
                url: "/pembayaran/tagihan/tagihan/" + id + "/" + idSiswa,
                method: "GET",
                success: function(response) {
                    console.log(response)
                    $("#nominal_spp_label").html(`Nominal Tagihan ` + response.data.nama_tagihan +
                        ':')
                    $("#nominal").val(response.nominal_rupiah)
                    $("#jumlah_bayar").val(response.data.nominal)
                    // $("#dibayar").val(response.data.nominal)


                    var dibayar = $("#dibayar").val()
                    var total_bayar = $("#jumlah_bayar").val()
                    var nominaltagihan = response.data_tagihansiswa.nominal
                    var hasil_bayar = (total_bayar - nominaltagihan)

                    if (hasil_bayar < 0) {
                        hasil_bayar = 0;
                    }

                    var formatter = new Intl.NumberFormat('ID', {
                        style: 'currency',
                        currency: 'idr',
                    })
                    $("#total_kekurangan").val(formatter.format(hasil_bayar))

                    //datable
                    $(function() {
                        var tagihansiswa_id = $("#tagihansiswa_id").val()
                        $("#dataTable2").DataTable().destroy();
                        var table = $("#dataTable2").DataTable({
                            // retrieve: true,
                            searching: false,
                            paging: false,
                            info: false,
                            processing: true,
                            serverSide: true,
                            "responsive": true,
                            ajax: "{{ url('pembayaran/tagihan/list-pembayaran-tagihan') }}" +
                                "/" +
                                nisn +
                                "/" + tagihansiswa_id,
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'id'
                                },
                                {
                                    data: 'tanggal_bayar',
                                    name: 'tanggal_bayar'
                                },
                                {
                                    data: 'nominal',
                                    name: 'nominal'
                                },
                                {
                                    data: 'metode',
                                    name: 'metode'
                                },
                                {
                                    data: 'status',
                                    name: 'status'
                                },
                            ]
                        });

                    });
                }
            })
        })

        $(document).on("change", "#dibayar", function() {
            var id = $('#tagihansiswa_id').val()
            var idSiswa = $("#siswa_id").val()

            let nominaltagihan = 0;
            $.ajax({
                url: "/pembayaran/tagihan/tagihan/" + id + "/" + idSiswa,
                method: "GET",
                success: function(response) {
                    console.log(response)
                    nominaltagihan = response.data_tagihansiswa.nominal
                    if (nominaltagihan == 0) {
                        nominaltagihan = response.data.nominal
                    }
                    var dibayar = $("#dibayar").val()
                    var total_bayar = $("#jumlah_bayar").val()
                    var hasil_bayar = (nominaltagihan - dibayar)

                    var formatter = new Intl.NumberFormat('ID', {
                        style: 'currency',
                        currency: 'idr',
                    })
                    $("#total_kekurangan").val(formatter.format(hasil_bayar))
                }
            })
        })
    </script>

    <script></script>
@endpush
