@extends('super.master-layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List Mahasiswa')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-6">
            <form id="importForm" action="{{ route('master.mahasiswa.import.proses') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="excel_file">
                    <button id="importButton" class="btn btn-primary" type="button">Import Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Mahasiswa</h4>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="table-utama" class="table table-sm table-striped table-bordered table-hover"
                            style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
                                    <th>Prodi</th>
                                    <th>Jumlah Terlambat (Menit)</th>
                                    <th>Jumlah Alfa (Menit)</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalData = count($dataMahasiswa);
                                @endphp
                                @foreach ($dataMahasiswa as $nomor => $value)
                                <tr>
                                    <td>{{ $totalData - $nomor }}</td>
                                    <td>{{ $value['kode_user'] }}</td>
                                    <td>{{ $value['nama_user'] }}</td>
                                    <td>{{ $value['kelas'] }}</td>
                                    <td>{{ $value['semester'] }}</td>
                                    <td>{{ $value['prodi'] }}</td>
                                    <td>{{ $value['jumlah_terlambat'] }}</td>
                                    <td>{{ $value['jumlah_alfa'] }}</td>
                                    <td>{{ $value['total'] }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm m-1"
                                            href="{{ route('master.mahasiswa.edit', ['id_mahasiswa' => $value->id_mahasiswa]) }}">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                            data-bs-target="#alertConfirm{{ $value['id_mahasiswa'] }}">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="alertConfirm{{ $value['id_mahasiswa'] }}"
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
                                                        <a href="{{ route('mahasiswa.delete.proses', ['id_mahasiswa' => $value['id_mahasiswa']]) }}"
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
@endsection

@section('js-content')
<script>
    $(document).ready(function() {
        $('#table-utama').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Ekspor semua kolom kecuali kolom terakhir (Aksi)
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Ekspor semua kolom kecuali kolom terakhir (Aksi)
                    }
                }
            ],
            order: [[0, 'desc']] // Sort by the first column (No)
        });
        // SweetAlert for import confirmation
        $('#importButton').click(function() {
            Swal.fire({
                title: 'Apakah Anda yakin untuk mengimport data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Import',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#importForm').submit(); // Submit the form if confirmed
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

@section('css-content')
@endsection