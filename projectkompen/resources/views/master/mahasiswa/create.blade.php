@extends('super.master-layout')
@section('custom-css')
@endsection

@section('title')
@section('content')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah Mahasiswa</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.mahasiswa.listmahasiswa') }}">List Mahasiswa</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="mb-0">Tambah Mahasiswa</h4>
                </div>
                <hr>
                <form id="job-form" method="POST" action="{{ route('mahasiswa.add.proses') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">NIM:</label>
                            <input class="form-control form-control-sm select-element" type="text" name="kode_user"
                                id="kode_user" placeholder="Masukkan Nim" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control form-control-sm select-element" id="nama_mahasiswa"
                                name="nama_mahasiswa" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Terlambat (menit)</label>
                            <input type="text" class="form-control form-control-sm select-element" id="jumlah_terlambat"
                                name="jumlah_terlambat" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Alfa (jam)</label>
                            <input type="text" class="form-control form-control-sm select-element" id="jumlah_alfa"
                                name="jumlah_alfa" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control form-control-sm select-element" id="total"
                                name="total" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Pilih Pekerjaan</label>
                            <select class="form-control form-control-sm select2" name="pekerjaan[]"
                                id="pekerjaan-select" multiple required>
                                @foreach($pekerjaanList as $pekerjaan)
                                <option value="{{ $pekerjaan->kode_pekerjaan }}"
                                    data-jam="{{ $pekerjaan->jam_pekerjaan }}">
                                    {{ $pekerjaan->kode_pekerjaan }} - {{ $pekerjaan->nama_pekerjaan }}
                                    (Jam: {{ $pekerjaan->jam_pekerjaan }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <label class="form-label">Perkiraan Sisa Jam</label>
                            <input type="text" class="form-control form-control-sm" id="perkiraan-sisa-jam" readonly>
                        </div>
                    </div>

                    <div class="row justify-content-left">
                        <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                            <button class="btn btn-primary" type="button" id="save-button"
                                onclick="showConfirmation()">Simpan</button>
                            <a href="{{ route('master.mahasiswa.listmahasiswa') }}" class="btn btn-secondary"
                                style="margin-left: 10px;"><i class="bx bx-x me-1"></i>Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#pekerjaan-select').change(function() {
            calculateRemainingHours();
        });

        function calculateRemainingHours() {
            let total = parseInt($('#total').val());
            let selectedJobs = $('#pekerjaan-select option:selected');
            let totalJobHours = 0;

            selectedJobs.each(function() {
                totalJobHours += parseInt($(this).data('jam'));
            });

            let remainingHours = total - totalJobHours;
            $('#perkiraan-sisa-jam').val(remainingHours);
        }
    });

    function showConfirmation() {
        let allSelects = document.querySelectorAll('.select-element');
        let isValid = true;

        allSelects.forEach(function(select) {
            if (select.value === '') {
                isValid = false;
                return;
            }
        });

        if (isValid) {
            Swal.fire({
                title: 'Apakah anda yakin akan menambah data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Masih ada yang kosong!',
                icon: 'error',
            });
        }
    }
</script>
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