@extends('layouts.backend.app')
@section('title', 'Data Tagihan')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- Sweetalert 2 -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/sweetalert2/sweetalert2.min.css">
@endpush
@section('content_title', 'Data Tagihan')
@section('content')
    <x-alert></x-alert>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('create-parmas')
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#createModal">
                            <i class="fas fa-plus fa-fw"></i> Tambah Data
                        </a>
                    @endcan
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="dataTable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tagihan</th>
                                <th>Nominal</th>
                                <th>Kelas</th>
                                {{-- <th>Created</th>
                                <th>Updated</th> --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                {{-- <td></td>
                                <td></td> --}}
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

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="store">
                    <div class="modal-body">
                        <div class="alert alert-danger print-error-msg" style="display: none;">
                            <ul></ul>
                        </div>
                        <div class="form-group">
                            <label for="nama_tagihan">Nama Tagihan:</label>
                            <input required="" type="text" name="nama_tagihan" id="nama_tagihan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal:</label>
                            <input required="" type="text" name="nominal" id="nominal" class="form-control">
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="kelas_id">Kelas:</label>
                                <select required="" name="kelas_id" id="kelas_id" class="form-control select2bs4">
                                    <option disabled="" selected="">- PILIH KELAS -</option>
                                    @foreach ($kelas as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-fw"></i> SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Create Modal -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update">
                    <div class="modal-body">
                        <div class="alert alert-danger print-error-msg" style="display: none;">
                            <ul></ul>
                        </div>
                        <div class="form-group">
                            <label for="nama_tagihan_edit">Nama Tagihan:</label>
                            <input required="" type="hidden" readonly="" name="id" id="id_edit"
                                class="form-control">
                            <input required="" type="text" name="nama_tagihan" id="nama_tagihan_edit"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nominal_edit">Nominal:</label>
                            <input required="" type="text" name="nominal" id="nominal_edit" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-fw"></i> UPDATE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->

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
    <!-- Sweetalert 2 -->
    <script type="text/javascript"
        src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    @include('admin.tagihan.ajax')
@endpush
