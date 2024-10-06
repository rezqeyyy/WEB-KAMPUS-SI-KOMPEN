<!-- resources/views/mhsw/listpekerjaan.blade.php -->

@extends('super.master-layout')

@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List Disetujui Kepala Lab')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List Disetujui Kepala Lab</h4>
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
                                    <th>Total (Menit)</th>
                                    <th>Sisa (Menit)</th>
                                    <th>Status Approval 1</th>
                                    <th>Status Approval 2</th>
                                    <th>Status Approval 3</th>
                                    <th>Approval 3 By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPengajuan as $index => $pengajuan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pengajuan->id_pengajuan }}</td>
                                    <td>{{ $pengajuan->kode_kegiatan }}</td>
                                    <td>{{ $pengajuan->nama_user }}</td>
                                    <td>{{ $pengajuan->total }}</td>
                                    <td>{{ $pengajuan->sisa }}</td>
                                    <td>{{ $pengajuan->status_approval1 }}</td>
                                    <td>{{ $pengajuan->status_approval2 }}</td>
                                    <td>{{ $pengajuan->status_approval3 }}</td>
                                    <td>{{ $pengajuan->approval3_by }}</td>
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
            dom: 'lBfrtip', // Menambahkan huruf 'B' untuk tombol export
            buttons: [
                'excel', 'pdf', 'print' // Menambahkan tombol export Excel dan PDF
            ]
        });
    });
</script>
@endsection

@section('css-content')
@endsection