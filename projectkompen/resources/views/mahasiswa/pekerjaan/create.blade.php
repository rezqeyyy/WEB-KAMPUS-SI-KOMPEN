@extends('mhsw.layout')
@section('custom-css')
@endsection

@section('title', 'Pengambilan Pekerjaan Mahasiswa')
@section('content')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengambilan Pekerjaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Pengambilan Pekerjaan</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="mb-0">Pengambilan Pekerjaan</h4>
                </div>
                <hr>
                <form id="job-form" method="POST" action="{{ route('mahasiswa.pekerjaan.proses') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="status_approval1" id="status_approval1" value="Belum Upload">
                    <input type="hidden" name="semester" id="semester" value="{{ $mahasiswa->semester }}">
                    <input type="hidden" name="jumlah_terlambat" id="jumlah_terlambat"
                        value="{{ $mahasiswa->jumlah_terlambat }}">
                    <input type="hidden" name="jumlah_alfa" id="jumlah_alfa" value="{{ $mahasiswa->jumlah_alfa }}">
                    <div class="col-12">
                        <label class="form-label">NIM:</label>
                        <input class="form-control form-control-sm" type="text" name="kode_mahasiswa"
                            id="kode_mahasiswa" value="{{ $mahasiswa->kode_user }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control form-control-sm" id="nama_mahasiswa"
                            name="nama_mahasiswa" value="{{ $mahasiswa->nama_user }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control form-control-sm" id="kelas" name="kelas"
                            value="{{ $mahasiswa->kelas }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Prodi</label>
                        <input type="text" class="form-control form-control-sm" id="prodi" name="prodi"
                            value="{{ $mahasiswa->prodi }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Total Kompen (Menit)</label>
                        <input type="text" class="form-control form-control-sm" id="total" name="total"
                            value="{{ $mahasiswa->total }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Perkiraan Sisa Menit</label>
                        <input type="text" class="form-control form-control-sm" id="perkiraan-sisa-jam" readonly>
                    </div>
                    <br>
                    <div id="job-selection-container">
                        <div class="row mb-3 job-selection">
                            <div class="mb-3 col-md-10">
                                <label class="form-label">Pilih Pekerjaan</label>
                                <select class="form-control form-control-sm select2" name="pekerjaan[]" required>
                                    @foreach($pekerjaanList as $pekerjaan)
                                    <option value="{{ $pekerjaan->kode_pekerjaan }}"
                                        data-nama="{{ $pekerjaan->nama_pekerjaan }}"
                                        data-jam="{{ $pekerjaan->jam_pekerjaan }}"
                                        data-batas="{{ $pekerjaan->batas_pekerja }}"
                                        data-penanggungjawab="{{ $pekerjaan->penanggung_jawab }}"
                                        data-id-penanggung-jawab="{{ $pekerjaan->id_penanggung_jawab }}">
                                        {{ $pekerjaan->kode_pekerjaan }} - {{ $pekerjaan->nama_pekerjaan }}
                                        (Jam Pekerjaan (menit): {{ $pekerjaan->jam_pekerjaan }}, Batas: {{
                                        $pekerjaan->batas_pekerja }},
                                        Penanggung Jawab: {{ $pekerjaan->penanggung_jawab }})

                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-job">-</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success" id="add-job">+</button>
                        </div>
                    </div>


                </form>
                <br>
                <div class="row justify-content-left">
                    <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                        <button class="btn btn-primary" type="button" id="button"
                            onclick="showConfirmation()">Simpan</button>
                        <a href="{{ route('mahasiswa.listpekerjaan') }}" class="btn btn-secondary"
                            style="margin-left: 10px;"><i class="bx bx-x me-1"></i>Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    function showConfirmation() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Anda yakin ingin menyimpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#job-form').submit(); // Submit form if user confirms
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
    $('.select2').select2();

    let firstSelectedPenanggungJawab = null;

    function calculateRemainingHours() {
        let total = parseInt($('#total').val());
        let selectedJobs = $('#job-selection-container select');
        let totalJobHours = 0;

        // Calculate the total hours of the selected jobs
        selectedJobs.each(function() {
            totalJobHours += parseInt($(this).find('option:selected').data('jam') || 0);
        });

        let remainingHours = 0;

        if (total > 1500) {
            remainingHours = total - ((totalJobHours > 1500) ? 1500 : totalJobHours);
        } else {
            remainingHours = total - totalJobHours;
            if (remainingHours < 0) {
                remainingHours = 0;
            }
        }

        $('#perkiraan-sisa-jam').val(remainingHours);
    }

    $('#add-job').click(function() {
        var jobSelection = `
            <div class="row mb-3 job-selection">
                <div class="mb-3 col-md-10">
                    <label class="form-label">Pilih Pekerjaan</label>
                    <select class="form-control form-control-sm select2" name="pekerjaan[]" required>
                        @foreach($pekerjaanList as $pekerjaan)
                        <option value="{{ $pekerjaan->kode_pekerjaan }}"
                            data-nama="{{ $pekerjaan->nama_pekerjaan }}"
                            data-jam="{{ $pekerjaan->jam_pekerjaan }}"
                            data-batas="{{ $pekerjaan->batas_pekerja }}"
                            data-penanggungjawab="{{ $pekerjaan->penanggung_jawab }}">
                            {{ $pekerjaan->kode_pekerjaan }} - {{ $pekerjaan->nama_pekerjaan }}
                            (Jam: {{ $pekerjaan->jam_pekerjaan }}, Batas: {{ $pekerjaan->batas_pekerja }},
                            Penanggung Jawab: {{ $pekerjaan->penanggung_jawab }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-job">-</button>
                </div>
            </div>
        `;
        $('#job-selection-container').append(jobSelection);
        $('.select2').select2();

        // Check for duplicate jobs after adding
        let duplicate = checkDuplicateJobs();

        if (duplicate) {
            Swal.fire({
                title: 'Error!',
                text: 'Tidak bisa memilih pekerjaan dengan kode yang sama.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Example: Remove the last added job selection
            $(this).closest('.job-selection').remove();
        } else {
            calculateRemainingHours();
            checkPenanggungJawabSelection(); // Check penanggung jawab selection after adding
        }
    });

    $(document).on('click', '.remove-job', function() {
        $(this).closest('.job-selection').remove();
        calculateRemainingHours();
        checkPenanggungJawabSelection(); // Check penanggung jawab selection after removing
    });

    function checkDuplicateJobs() {
        let selectedJobCodes = [];
        let duplicateFound = false;

        $('#job-selection-container select').each(function() {
            let jobCode = $(this).val();

            if (selectedJobCodes.includes(jobCode)) {
                duplicateFound = true;
                return false; // Exit the loop early
            } else {
                selectedJobCodes.push(jobCode);
            }
        });

        return duplicateFound;
    }

    function checkPenanggungJawabSelection() {
        let firstPenanggungJawab = null;
        let penanggungJawabSelected = true; // Assume initially true

        $('#job-selection-container select').each(function(index, element) {
            let penanggungJawab = $(element).find('option:selected').data('penanggungjawab');

            if (index === 0) {
                firstPenanggungJawab = penanggungJawab;
            } else {
                if (penanggungJawab !== firstPenanggungJawab) {
                    penanggungJawabSelected = false;
                    return false; // Exit the loop early
                }
            }
        });

        if (!penanggungJawabSelected) {
            Swal.fire({
                title: 'Error!',
                text: 'Harap pilih pekerjaan dengan penanggung jawab yang sama.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Example: Remove the last added job selection
            $(this).closest('.job-selection').remove();
        }

        // Set status_approval1 based on penanggung jawab selection
        if (penanggungJawabSelected) {
            $('#status_approval1').val('Belum Upload');
        } else {
            $('#status_approval1').val('Belum Upload');
        }
    }

    $(document).on('change', '#job-selection-container select', function() {
        let duplicate = checkDuplicateJobs();

        if (duplicate) {
            Swal.fire({
                title: 'Error!',
                text: 'Tidak bisa memilih pekerjaan dengan kode yang sama.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            $(this).val('');
        } else {
            calculateRemainingHours();
            checkPenanggungJawabSelection(); // Check penanggung jawab selection after change
        }
    });

    $('#save-button').click(function() {
        let duplicate = checkDuplicateJobs();

        if (duplicate) {
            Swal.fire({
                title: 'Error!',
                text: 'Tidak bisa memilih pekerjaan dengan kode yang sama.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            $('#job-form').submit();
        }
    });
});

</script>
@endsection