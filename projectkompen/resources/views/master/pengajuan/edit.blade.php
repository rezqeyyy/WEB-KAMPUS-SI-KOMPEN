@extends('super.master-layout')

@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
@endsection

@section('title', 'Edit Pengajuan Detail')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Pengajuan Detail</h4>
                </div>
                <div class="card-body">
                    <form id="editForm"
                        action="{{ route('master.pengajuan.edit.proses', ['kode_kegiatan' => $dataPengajuan->kode_kegiatan]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Kode Kegiatan -->
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="kode_kegiatan">Kode Kegiatan</label>
                                <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan"
                                    value="{{ $dataPengajuan->kode_kegiatan }}" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control form-control-sm" id="kelas" name="kelas"
                                value="{{ $dataPengajuan->kelas }}" readonly>
                        </div>

                        <!-- Nama Pekerjaan, Jam Pekerjaan, and Batas Pekerja in one row -->
                        @foreach($pengajuanDetail as $key => $value)
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="kode_pekerjaan">Kode Pekerjaan</label>
                                <input type="text" class="form-control" id="kode_pekerjaan" name="kode_pekerjaan[]"
                                    value="{{ $value->pekerjaan->kode_pekerjaan }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_pekerjaan">Nama Pekerjaan</label>
                                 <textarea rows="7" readonly class="form-control" id="nama_pekerjaan" name="nama_pekerjaan[]">{{ $value->pekerjaan->nama_pekerjaan }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="jam_pekerjaan">Jam Pekerjaan</label>
                                <input type="text" class="form-control" id="jam_pekerjaan" name="jam_pekerjaan[]"
                                    value="{{ $value->pekerjaan->jam_pekerjaan }}" readonly>
                            </div>
                        </div>
                        <br>

                        <!-- Before Pekerjaan -->
                        <div class="form-group">
                            <label for="before_pekerjaan">Before Pekerjaan</label>
                            @if($value->before_pekerjaan)
                            <a href="{{ asset('storage/before/'.$value->before_pekerjaan) }}"
                                data-lightbox="before-pekerjaan-{{ $value->id }}" data-title="Before Pekerjaan">
                                <img src="{{ asset('storage/before/'.$value->before_pekerjaan) }}" alt="Before Pekerjaan"
                                    style="max-width: 200px; margin-top: 10px;">
                            </a>
                            @else
                            <p>No Before Pekerjaan image available</p>
                            @endif
                        </div>

                        <!-- After Pekerjaan -->
                        <div class="form-group">
                            <label for="after_pekerjaan">After Pekerjaan</label>
                            @if($value->after_pekerjaan)
                            <a href="{{ asset('storage/after/'.$value->after_pekerjaan) }}"
                                data-lightbox="after-pekerjaan-{{ $value->id }}" data-title="After Pekerjaan">
                                <img src="{{ asset('storage/after/'.$value->after_pekerjaan) }}" alt="After Pekerjaan"
                                    style="max-width: 200px; margin-top: 10px;">
                            </a>
                            @else
                            <p>No After Pekerjaan image available</p>
                            @endif
                        </div>
                        <hr>
                        @endforeach

                        <!-- Bukti Tambahan -->
                        @if($dataPengajuan->bukti_tambahan)
                        <div class="form-group">
                            <label for="bukti_tambahan">Bukti Tambahan</label>
                            <a href="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                data-lightbox="bukti-tambahan" data-title="Bukti Tambahan">
                                <img src="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                    alt="Bukti Tambahan" style="max-width: 200px; margin-top: 10px;">
                            </a>
                        </div>
                        @endif
                        <hr>

                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
                            <a href="{{ route('master.pengajuan.listpengajuan') }}" class="btn btn-secondary">Back</a>
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
<script>
    // SweetAlert2 confirmation before form submission
    document.getElementById('submitBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to submit this form!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit(); // Submit the form
            }
        });
    });
</script>
@endsection