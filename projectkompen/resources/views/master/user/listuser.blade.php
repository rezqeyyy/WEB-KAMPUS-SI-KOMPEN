@extends('super.master-layout')
@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List User')
@section('content')
<div class="container-fluid mt-10">
    <div class="row mb-3">
        <div class="col-md-1">
            <a href="{{ route('master.user.create') }}"><button class="btn btn-primary float-end">Tambah
                    Data</button></a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List User</h4>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table id="table-utama" class="table table-sm table-striped table-bordered table-hover"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>NIP</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalData = count($dataUser);
                                @endphp
                                @foreach ($dataUser as $nomor => $value)
                                <tr>
                                    <td>{{ $totalData - $nomor }}</td>
                                    <td>{{ $value['nama_user'] }}</td>
                                    <td>{{ $value['kode_user'] }}</td>
                                    <td>{{ $value['email'] }}</td>
                                    <td>{{ $value['role'] }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm m-1"
                                            href="{{ route('master.user.edit', ['id_user' => $value->id_user]) }}">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                            data-bs-target="#alertConfirm{{ $value['id_user'] }}">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="alertConfirm{{ $value['id_user'] }}"
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
                                                        <form action="{{ route('user.delete.proses') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id_user"
                                                                value="{{ $value['id_user'] }}">
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
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
                </nav>
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
        // Inisialisasi DataTables pada tabel
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
            ],
            order: [0, 'asc'] // Sort by the first column (No)
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