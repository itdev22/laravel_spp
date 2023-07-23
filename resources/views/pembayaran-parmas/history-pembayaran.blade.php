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
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle float-right" type="button" data-toggle="dropdown" aria-expanded="false">
                            Filter
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item filter-item" href="#" data-filter="" >Semua</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPS 1" >X IPS 1</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPA 1" >X IPA 1</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="XI IPS 1" >XI IPS 1</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="XI IPA 1" >XI IPA 1</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="XI IPS 2" >XI IPS 2</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPS 2">X IPS 2</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPA 2">X IPA 2</a>
                            <div class="dropdown-pagination">
                                <button class="btn btn-link previous-page">&lt; Prev</button>
                                <button class="btn btn-link next-page">Next &gt;</button>
                            </div>

                            </div>
                        </div>
                    <table id="dataTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Nisn</th>
                                <th>Tanggal Bayar</th>
                                <th>Nama Petugas</th>
                                <th>Untuk Bulan</th>
                                <th>Untuk Tahun</th>
                                <th>Nominal</th>
                                <th>Metode</th>
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
                ajax: "{{ route('parmas.pembayaran.history-pembayaran') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'siswa.nama_siswa',
                        name: 'siswa.nama_siswa'
                    },
                    {
                        data: 'siswa.kelas.nama_kelas',
                        name: 'siswa.kelas.nama_kelas'
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
                        data: 'bulan_bayar',
                        name: 'bulan_bayar'
                    },
                    {
                        data: 'tahun_bayar',
                        name: 'tahun_bayar'
                    },
                    {
                        data: 'jumlah_bayar',
                        name: 'jumlah_bayar'
                    },
                    {
                        data: 'metode',
                        name: 'metode'
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
            $(".filter-item").on("click", function() {
                var filterValue = $(this).data("filter");

                if (filterValue === "") {
                    table.column(3).search("").draw(); // Hapus filter kelas
                } else {
                    table.column(3).search(filterValue).draw(); // Terapkan filter kelas
                }
            });

            $(function() {
    var itemsPerPage = 5; // Jumlah item yang ditampilkan per halaman
    var currentPage = 1; // Halaman saat ini

    // Fungsi untuk menampilkan item-item pada halaman yang diinginkan
    function showItemsByPage(page) {
        var startIndex = (page - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;

        $(".filter-item").hide();
        $(".filter-item").slice(startIndex, endIndex).show();

        // Menampilkan/menyembunyikan tombol navigasi halaman sesuai dengan halaman saat ini
        if (page === 1) {
            $(".previous-page").hide();
        } else {
            $(".previous-page").show();
        }

        if (endIndex >= $(".filter-item").length) {
            $(".next-page").hide();
        } else {
            $(".next-page").show();
        }
    }

    // Menampilkan halaman pertama saat halaman dimuat
    showItemsByPage(currentPage);

    // Event handler untuk tombol navigasi halaman sebelumnya
    $(".previous-page").on("click", function() {
        if (currentPage > 1) {
            currentPage--;
            showItemsByPage(currentPage);
        }
    });

    // Event handler untuk tombol navigasi halaman berikutnya
    $(".next-page").on("click", function() {
        if (currentPage < Math.ceil($(".filter-item").length / itemsPerPage)) {
            currentPage++;
            showItemsByPage(currentPage);
        }
    });
});
        });
    </script>
@endpush
