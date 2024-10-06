@extends('mhsw.layout')

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
    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">Data Mahasiswa</h4>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="table-utama"
                            class="table table-sm table-striped table-bordered table-border table-hover"
                            style="width:100%">
                            <thead class="th-dark">
                                <tr>
                                    {{-- <th>No</th> --}}
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Email</th>
                                    <th>No Hp</th>
                                    <th>Kelas</th>
                                    <th>Prodi</th>
                                    <th>Jumlah Terlambat(Menit)</th>
                                    <th>Jumlah Alfa(Menit)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataMahasiswa as $nomor => $value)
                                <tr>
                                    {{-- <td>{{ $totalData - $nomor }}</td> --}}
                                    <td>{{ $value->kode_user }}</td>
                                    <td>{{ $value->nama_user }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->notelp }}</td>
                                    <td>{{ $value->kelas }}</td>
                                    <td>{{ $value->prodi }}</td>
                                    <td>{{ $value->jumlah_terlambat }}</td>
                                    <td>{{ $value->jumlah_alfa }}</td>
                                    <td>{{ $value->total }}</td>
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
            order: [6, 'desc'] // Sort by the index
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