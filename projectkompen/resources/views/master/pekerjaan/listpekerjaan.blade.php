@extends('super.master-layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }

    .btn-space {
        margin-right: 20px;
    }
</style>
@endsection

@section('title', 'List Pekerjaan')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-6 d-flex align-items-center">
            <form id="importForm" action="{{ route('master.pekerjaan.import.proses') }}" method="POST"
                enctype="multipart/form-data" class="d-flex align-items-center">
                @csrf
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="excel_file">
                    <button id="importButton" class="btn btn-primary btn-space btn-large" type="button">Import
                        Data</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <a href="{{ route('master.pekerjaan.create') }}" class="btn btn-primary">Tambah Data</a>
            <button id="delete-all-button" class="btn btn-danger">Delete Semua Pekerjaan</button>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Pekerjaan</h4>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="table-utama" class="table table-sm table-striped table-bordered table-hover"
                            style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pekerjaan</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Jam Pekerjaan (menit)</th>
                                    <th>Limit Pekerja</th>
                                    <th>Id PJ</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalData = count($dataPekerjaan);
                                @endphp
                                @foreach ($dataPekerjaan as $nomor => $value)
                                <tr>
                                    <td>{{ $totalData - $nomor }}</td>
                                    <td>{{ $value['kode_pekerjaan'] }}</td>
                                    <td class="text-wrap mw-100">{{ $value['nama_pekerjaan'] }}</td>
                                    <td>{{ $value['jam_pekerjaan'] }}</td>
                                    <td>{{ $value['batas_pekerja'] < 0 ? 0 : $value['batas_pekerja'] }}</td>
                                    <td>{{ $value['id_penanggung_jawab'] }}</td>
                                    <td>{{ $value['penanggung_jawab'] }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm btn-space"
                                            href="{{ route('master.pekerjaan.edit', ['id_pekerjaan' => $value['id_pekerjaan']]) }}">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>

                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#alertConfirm{{ $value['id_pekerjaan'] }}">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="alertConfirm{{ $value['id_pekerjaan'] }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content bg-white">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah Anda yakin akan menghapus data?
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <a href="{{ route('pekerjaan.delete.proses', ['id_pekerjaan' => $value['id_pekerjaan']]) }}"
                                                            class="btn btn-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete All Confirmation -->
<div class="modal fade" id="deleteAllModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title">Apakah Anda yakin akan menghapus semua data?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="{{ route('master.pekerjaan.deleteAll.proses') }}" class="btn btn-danger">Hapus Semua</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<!-- SweetAlert for Import Confirmation -->
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#table-utama').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Aksi)
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Aksi)
                    }
                }
            ]
        });

        // SweetAlert for Import Confirmation
        $('#importButton').click(function() {
            Swal.fire({
                title: 'Apakah Anda yakin akan mengimport data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Import!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#importForm').submit(); // Submit the form if confirmed
                }
            });
        });

        // SweetAlert for Delete All Confirmation
        $('#delete-all-button').click(function() {
            Swal.fire({
                title: 'Apakah Anda yakin akan menghapus semua data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteAllModal').modal('show');
                }
            });
        });
    });
</script>
@if (Session::has('alert-success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'success',
        title: '{{ Session::get('alert-success') }}'
    });
</script>
@endif

@if (Session::has('alert-error'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'error',
        title: '{{ Session::get('alert-error') }}'
    });
</script>
@endif
@endsection