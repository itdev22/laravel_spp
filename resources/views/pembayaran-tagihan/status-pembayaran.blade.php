@extends('layouts.backend.app')
@section('title', 'Status Pembayaran Siswa')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush
@section('content_title', 'Data Siswa')
@section('content')
    <x-alert></x-alert>
    <div class="row">
        <div class="col-12">
            <div class="card">
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
                            <a class="dropdown-item filter-item" href="#" data-filter="XI IPS 5" >XI IPS 5</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="XII IPS 5" >XI IPS 5</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPS 6">X IPS 6</a>
                            <a class="dropdown-item filter-item" href="#" data-filter="X IPA 6">X IPA 6</a>
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
                                <th>Nisn</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Detail</th>
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
                ajax: "{{ route('tagihan.pembayaran.status-pembayaran') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'nisn',
                        name: 'nisn'
                    },
                    {
                        data: 'kelas.nama_kelas',
                        name: 'kelas.nama_kelas'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
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
