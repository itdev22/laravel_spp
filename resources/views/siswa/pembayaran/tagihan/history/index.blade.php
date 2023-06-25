@extends('layouts.backend.app')
@section('title', 'Data History Pembayaran')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush
@section('content_title', 'History Pembayaran')
@section('content')
    <x-alert></x-alert>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="dataTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Untuk Tagihan</th>
                                <th>Nisn</th>
                                <th>Tanggal Bayar</th>
                                <th>Nama Petugas</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Print</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
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
    <script>
        $(function() {

            var table = $("#dataTable2").DataTable({
                processing: true,
                serverSide: true,
                "responsive": true,
                ajax: "{{ route('siswa.history-tagihan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'siswa.nama_siswa',
                        name: 'siswa.nama_siswa'
                    },
                    {
                        data: 'tagihansiswa.tagihan.nama_tagihan',
                        name: 'tagihansiswa.tagihan.nama_tagihan'
                    },
                    {
                        data: 'siswa.nisn',
                        name: 'siswa.nisn'
                    },
                    {
                        data: 'tanggal_bayar',
                        name: 'tanggal_bayar'
                    },
                    {
                        data: 'petugas.nama_petugas',
                        name: 'petugas.nama_petugas'
                    },
                    {
                        data: 'metode',
                        name: 'metode'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'url_payment',
                        name: 'url_payment',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="${data}" target="_blank">Payment</a>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });

        });
    </script>
@endpush
