@extends('mhsw.layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List Pengajuan Pekerjaan')

@section('content')
<div class="container-fluid mt-4">
    <a href="{{ route('mahasiswa.pekerjaan.create') }}">
        <button class="btn btn-primary">Tambah Pengajuan</button>
    </a>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Pengajuan Pekerjaan</h4>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="table-utama"
                            class="table table-sm table-striped table-bordered table-border table-hover"
                            style="width:100%">
                            <thead class="th-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pengajuan</th>
                                    <th>Kode Kegiatan</th>
                                    <th>Nama User</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
                                    <th>Total (Menit)</th>
                                    <th>Sisa</th>
                                    <th>Status Approval 1</th>
                                    <th>Status Approval 2</th>
                                    <th>Status Approval 3</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPengajuan as $index => $pengajuan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pengajuan->id_pengajuan }}</td>
                                    <td>{{ $pengajuan->kode_kegiatan }}</td>
                                    <td>{{ $pengajuan->nama_user }}</td>
                                    <td>{{ $pengajuan->kelas }}</td>
                                    <td>{{ $pengajuan->semester }}</td>
                                    <td>{{ $pengajuan->total }}</td>
                                    <td>{{ $pengajuan->sisa }}</td>
                                    <td>{{ $pengajuan->status_approval1 }}</td>
                                    <td>{{ $pengajuan->status_approval2 }}</td>
                                    <td>{{ $pengajuan->status_approval3 }}</td>
                                    <td>
                                        @if ($pengajuan->status_approval1 != 'Disetujui' || $pengajuan->status_approval2
                                        != 'Disetujui' || $pengajuan->status_approval3 != 'Disetujui')
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('mahasiswa.pekerjaan.edit', ['kode_kegiatan' => $pengajuan->kode_kegiatan]) }}">
                                            <i class="fas fa-eye"></i> Teruskan
                                        </a>

                                        <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete('{{ route('mahasiswa.pengajuan.delete', ['kode_kegiatan' => $pengajuan->kode_kegiatan]) }}')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                        @endif

                                        @if ($pengajuan->status_approval1 == 'Disetujui' && $pengajuan->status_approval2
                                        == 'Disetujui' && $pengajuan->status_approval3 == 'Disetujui')
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('mahasiswa.surat', ['kode_kegiatan' => $pengajuan->kode_kegiatan]) }}">
                                            <i class="fas fa-file-alt"></i> Surat
                                        </a>
                                        @endif
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
        // Initialize DataTables on the table
        $('#table-utama').DataTable({
            dom: 'lfrtip',
            order: [0, 'asc'] // Sort by the first column (No)
        });

     // SweetAlert alert when page is loaded
     Swal.fire({
            title: 'PENTING',
            html: '<ul>' +
                '<li>Ambil Pekerjaan hanya dengan satu pengawas kompen yang sama.</li>' +
                '<li>Ambil pekerjaan sampai perkiraan sisanya 0 untuk total kompen kurang dari 1500 menit (25 jam) atau sama dengan 1500 menit (25 jam).</li>' +
                '<li>Kalau total kompen lebih dari 1500 menit (25 jam) wajib untuk mengambil pekerjaan sampai dengan perkiraan sisa contoh (total jam kompen (menit) 1600, ambil pekerjaan sampai sisanya 100 menit (Perkurangan dari 1600 menit-1500 menit=100menit)).</li>' +
                '</ul>',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    });

    function confirmDelete(url) {
        Swal.fire({
            title: 'Anda akan menghapus pengajuan ini?',
            text: "Anda tidak dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        if (result.status === 'success') {
                            Swal.fire(
                                'Terhapus!',
                                'Pengajuan telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    },
                    error: function(err) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>

@if (Session::has('alert-success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000,
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
        timer: 1000,
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