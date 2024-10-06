@extends('mhsw.layout')

@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .form-row {
        margin-bottom: 20px;
    }

    .img-thumbnail {
        max-width: 200px;
    }
</style>
@endsection

@section('title', 'Edit Pengajuan Detail')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Pengajuan Detail</h4>
                </div>
                <div class="card-body">
                    <form id="edit-form" method="POST"
                        action="{{ route('mahasiswa.pekerjaan.update', ['kode_kegiatan' => $dataPengajuan->kode_kegiatan]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="oldid" value="{{ $dataPengajuan->kode_kegiatan }}">
                        <input type="hidden" name="status_approval1" id="status_approval1" value="Sudah Upload">

                        <!-- Nama User -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_user">Nama User</label>
                                <input type="text" class="form-control" id="nama_user" name="nama_user"
                                    value="{{ $dataPengajuan->nama_user }}" readonly>
                            </div>
                        </div>

                        <!-- Kode User -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_user">NIM</label>
                                <input type="text" class="form-control" id="kode_user" name="kode_user"
                                    value="{{ $dataPengajuan->kode_user }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kelas">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas"
                                    value="{{ $dataPengajuan->kelas }}" readonly>
                            </div>
                        </div>
                        <!-- Kode Kegiatan -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_kegiatan">Kode Kegiatan</label>
                                <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan"
                                    value="{{ $dataPengajuan->kode_kegiatan }}" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_kegiatan">Total (Menit)</label>
                                <input type="text" class="form-control" id="total" name="total"
                                    value="{{ $dataPengajuan->total }}" readonly>
                            </div>
                        </div>


                        <!-- Detail Pekerjaan -->
                        @foreach($pengajuanDetail as $key => $value)
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_pekerjaan">Kode Pekerjaan</label>
                                <input type="text" class="form-control" id="kode_pekerjaan" name="kode_pekerjaan[]"
                                    value="{{ $value->pekerjaan->kode_pekerjaan }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_pekerjaan">Nama Pekerjaan</label>
                                 <textarea rows="7" readonly class="form-control" id="nama_pekerjaan" name="nama_pekerjaan[]">{{ $value->pekerjaan->nama_pekerjaan }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_pekerjaan">Jam Pekerjaan (Menit)</label>
                                <input type="text" class="form-control" id="jam_pekerjaan" name="jam_pekerjaan[]"
                                    value="{{ $value->pekerjaan->jam_pekerjaan }}" readonly>
                            </div>
                        </div>

                        <!-- Foto Before dan After Pekerjaan -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="before_pekerjaan">Before Pekerjaan</label>
                                <input type="file" class="form-control" id="before_pekerjaan" name="before_pekerjaan[]">
                                @if($value->before_pekerjaan)
                                <a href="{{ asset('storage/before/'.$value->before_pekerjaan) }}"
                                    data-lightbox="before-pekerjaan-{{ $value->id }}" data-title="Before Pekerjaan">
                                    <img src="{{ asset('storage/before/'.$value->before_pekerjaan) }}"
                                        alt="Before Pekerjaan" class="img-thumbnail">
                                </a>
                                @else
                                <p>No Before Pekerjaan image available</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="after_pekerjaan">After Pekerjaan</label>
                                <input type="file" class="form-control" id="after_pekerjaan" name="after_pekerjaan[]">
                                @if($value->after_pekerjaan)
                                <a href="{{ asset('storage/after/'.$value->after_pekerjaan) }}"
                                    data-lightbox="after-pekerjaan-{{ $value->id }}" data-title="After Pekerjaan">
                                    <img src="{{ asset('storage/after/'.$value->after_pekerjaan) }}"
                                        alt="After Pekerjaan" class="img-thumbnail">
                                </a>
                                @else
                                <p>No After Pekerjaan image available</p>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <!-- Bukti Tambahan -->
                        @if($dataPengajuan->sisa && $dataPengajuan->sisa !== '0')
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="bukti_tambahan">Bukti Tambahan</label>
                                <input type="file" class="form-control" id="bukti_tambahan" name="bukti_tambahan">
                                @if($dataPengajuan->bukti_tambahan)
                                <br>
                                <a href="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                    data-lightbox="bukti-tambahan" data-title="Bukti Tambahan">
                                    <img src="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                        alt="Bukti Tambahan" class="img-thumbnail">
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Keterangan Approval1 (displayed only if status_approval1 is Ditolak) -->
                        @if($dataPengajuan->status_approval1 == 'Ditolak')
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="keterangan_approval1">Keterangan Approval1</label>
                                <textarea class="form-control" id="keterangan_approval1" name="keterangan_approval1"
                                    rows="3" readonly>{{ $dataPengajuan->keterangan_approval1 }}</textarea>
                            </div>
                        </div>
                        @endif

                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="submit-button">Submit</button>
                            <a href="{{ route('mahasiswa.pekerjaan.listdiambil') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('submit-button').addEventListener('click', function() {
        // Check if all before and after images are uploaded
        var allUploaded = true;

        // Iterate through all input files
        document.querySelectorAll('input[name^="before_pekerjaan"], input[name^="after_pekerjaan"]').forEach(function(input) {
            // Check if files are selected and not empty
            if (input.files.length === 0 && input.nextElementSibling.querySelector('img') === null) {
                allUploaded = false;
                return;
            }
        });

        // Check if "Bukti Tambahan" is required and uploaded
        var buktiTambahanRequired = document.getElementById('bukti_tambahan');
        if (buktiTambahanRequired && buktiTambahanRequired.value === '' && !buktiTambahanRequired.nextElementSibling) {
            allUploaded = false;
        }

        // If not all images are uploaded, show warning
        if (!allUploaded) {
            Swal.fire({
                title: 'Upload Gambar Terlebih Dahulu',
                text: 'Harap unggah semua gambar "Before Pekerjaan" dan "After Pekerjaan", serta "Bukti Tambahan" (jika ada) terlebih dahulu.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        } else {
            // If all images are uploaded, confirm submission
            Swal.fire({
                title: 'Apakah Anda yakin akan menyimpan perubahan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('edit-form').submit();
                }
            });
        }
    });
</script>
@endsection