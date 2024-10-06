<!-- resources/views/mhsw/listpekerjaan.blade.php -->

@extends('super.master-layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List pekerjaan')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Pengajuan</h4>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="table-utama"
                            class="table table-sm table-striped table-bordered table-border table-hover"
                            style="width:100%">
                            <thead class="th-dark">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check-all">
                                            <label class="form-check-label" for="check-all">
                                                Pilih Semua
                                            </label>
                                        </div>
                                    </th>
                                    <th>No</th>
                                    <th>ID Pengajuan</th>
                                    <th>Kode Kegiatan</th>
                                    <th>Nama User</th>
                                    <th>Total (Menit)</th>
                                    <th>Sisa (Menit)</th>
                                    <th>Status Approval 1</th>
                                    <th>Status Approval 2</th>
                                    <th>Status Approval 3</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPengajuan as $index => $pengajuan)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check{{ $index }}"
                                                name="selected_pengajuan[]" value="{{ $pengajuan->id_pengajuan }}">
                                            <label class="form-check-label" for="check{{ $index }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pengajuan->id_pengajuan }}</td>
                                    <td>{{ $pengajuan->kode_kegiatan }}</td>
                                    <td>{{ $pengajuan->nama_user }}</td>
                                    <td>{{ $pengajuan->total }}</td>
                                    <td>{{ $pengajuan->sisa }}</td>
                                    <td>{{ $pengajuan->status_approval1 }}</td>
                                    <td>{{ $pengajuan->status_approval2 }}</td>
                                    <td>{{ $pengajuan->status_approval3 }}</td>
                                    <td>
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('plp.edit', ['kode_kegiatan' => $pengajuan->kode_kegiatan]) }}">
                                            <i class="fas fa-eye"></i> Teruskan
                                        </a>
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

    <div class="row mt-3">
        <div class="col-12 text-right">
            <button class="btn btn-success" onclick="submitSemuaPengajuan()">
                <i class="fas fa-check-double"></i> Submit Semua Pengajuan
            </button>
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
            order: [1, 'asc'] // Sort by the second column (No)
        });

        // Handle select all checkbox
        $('#check-all').change(function() {
            var checkboxes = $(this).closest('table').find('tbody input[type="checkbox"]');
            checkboxes.prop('checked', $(this).prop('checked'));
        });
    });

    function approvePengajuan(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menyetujui pengajuan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form to approve the request
                $.ajax({
                    type: 'POST',
                    url: '{{ route('plp.approveSelected') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        selected_pengajuan: [id]
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // Reload halaman setelah sukses
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyetujui pengajuan.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }

    function submitSemuaPengajuan() {
        var selectedPengajuan = [];
        $("input[name='selected_pengajuan[]']:checked").each(function () {
            selectedPengajuan.push($(this).val());
        });

        if (selectedPengajuan.length > 0) {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyetujui semua pengajuan yang dipilih?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form to approve selected requests
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('plp.approveSelected') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            selected_pengajuan: selectedPengajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload(); // Reload halaman setelah sukses
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menyetujui pengajuan.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Pengajuan',
                text: 'Pilih setidaknya satu pengajuan untuk disetujui.',
                confirmButtonText: 'OK'
            });
        }
    }
</script>

@if (Session::has('alert-success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get('alert-success') }}',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif

@if (Session::has('alert-error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '{{ Session::get('alert-error') }}',
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif
@endsection

@section('css-content')
@endsection