@extends('super.master-layout')
@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List Setup')
@section('content')
<div class="container-fluid mt-4">
    <a href="{{ route('master.setup.create') }}">
        <button class="btn btn-primary">Tambah Data</button>
    </a>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Setup</h4>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="table-utama"
                            class="table table-sm table-striped table-bordered table-border table-hover"
                            style="width:100%">
                            <thead class="th-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama User</th>
                                    <th>Role</th>
                                    <th>Tanggal Bertugas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalData = count($dataBertugas);
                                @endphp
                                @foreach ($dataBertugas as $nomor => $value)
                                <tr>
                                    <td>{{ $totalData - $nomor }}</td>
                                    <td>{{ $value['kode_user'] }}</td>
                                    <td>{{ $value['nama_user'] }}</td>
                                    <td>{{ $value['role'] }}</td>
                                    <td>{{ $value['tgl_bertugas'] }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm m-1"
                                            href="{{ route('master.setup.edit', ['id_setup_bertugas' => $value->id_setup_bertugas]) }}">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>

                                        <button class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                            data-bs-target="#alertConfirm{{ $value->id_setup_bertugas }}">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="alertConfirm{{ $value->id_setup_bertugas }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content bg-white">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Apakah anda yakin akan menghapus data?
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <a href="{{ route('setup.delete.proses', ['id_setup_bertugas' => $value['id_setup_bertugas']]) }}"
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
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables pada tabel
        $('#table-utama').DataTable({
            dom: 'lfrtip',
            order: [4, 'desc'] // Sort by the index
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