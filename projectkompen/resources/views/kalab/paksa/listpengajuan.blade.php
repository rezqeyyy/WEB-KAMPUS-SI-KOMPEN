@extends('super.master-layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'Daftar Semua Pengajuan Untuk Pengajuan Paksa')

@section('content')
<div class="container-fluid mt-4">

    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">Daftar Semua Pengajuan Untuk Pengajuan Paksa</h4>
                    </div>
                    <hr>

                    <form id="approveForm" action="{{ route('kalab.paksa.kalabCeklistPaksaAcc') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary btn-sm" id="checkAllBtn">
                                <i class="fas fa-check"></i> Centang Semua
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="table-utama" class="table table-sm table-striped table-bordered table-hover"
                                style="width:100%">
                                <!-- Table Headers -->
                                <thead class="th-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>ID Pengajuan</th>
                                        <th>Kode Kegiatan</th>
                                        <th>Nama User</th>
                                        <th>Kelas</th>
                                        <th>Semester</th>
                                        <th>Total</th>
                                        <th>Sisa</th>
                                        <th>Status Approval 1</th>
                                        <th>Status Approval 2</th>
                                        <th>Status Approval 3</th>
                                        <th>Aksi</th>
                                        <th>Select</th>
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
                                            <a class="btn btn-success btn-sm"
                                                href="{{ route('kalab.paksa.edit', ['kode_kegiatan' => $pengajuan->kode_kegiatan]) }}">
                                                <i class="fas fa-eye"></i> Teruskan
                                            </a>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="selected_pengajuan[]"
                                                    id="selected_pengajuan_{{ $pengajuan->id_pengajuan }}"
                                                    value="{{ $pengajuan->id_pengajuan }}">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Button to submit selected approvals -->
                        <div class="form-group mt-3">
                            <button type="button" class="btn btn-success" id="approveSelectedBtn">
                                <i class="fas fa-check"></i> Setujui Terpilih
                            </button>
                        </div>
                    </form>

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

        // Script to handle approve selected button click
        $('#approveSelectedBtn').click(function() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menyetujui pengajuan yang dipilih!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    document.getElementById('approveForm').submit();
                }
            });
        });

        // Script to handle check all button click
        $('#checkAllBtn').click(function() {
            var isChecked = $(this).hasClass('active');
            $('input[name="selected_pengajuan[]"]').prop('checked', !isChecked);
            $(this).toggleClass('active');
            if (!isChecked) {
                $(this).html('<i class="fas fa-times"></i> Batal Centang Semua');
            } else {
                $(this).html('<i class="fas fa-check"></i> Centang Semua');
            }
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