@extends('layouts.backend.app')
@section('title', 'Pembayaran')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush
@section('content_title', 'Pembayaran')
@section('content')
    <x-alert></x-alert>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="dataTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tagihan</th>
                                <th>Nominal</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                ajax: "{{ route('siswa.pembayaran-tagihan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'tagihan.nama_tagihan',
                        name: 'tagihan.nama_tagihan'
                    },
                    {
                        data: 'tagihan.nominal',
                        name: 'tagihan.nominal'
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
