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
                    <h4>Approval Pengawas</h4>
                </div>
                <div class="card-body">
                    <form id="editForm"
                        action="{{ route('pengawas.edit.proses', ['kode_kegiatan' => $dataPengajuan->kode_kegiatan]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="approval1_by" id="approval1_by" value="{{ $nama_user }}">
                        <input type="hidden" name="id_penanggung_jawab" id="id_penanggung_jawab"
                            value="{{ auth()->guard('pengguna')->user()->id_user }}">
                        <input type="hidden" name="penanggung_jawab" id="penanggung_jawab"
                            value="{{ auth()->guard('pengguna')->user()->nama_user }}">
                        <input type="hidden" name="oldid" value="{{ $dataPengajuan->kode_kegiatan }}">

                        <!-- Kode Kegiatan -->
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="kode_kegiatan">Kode Kegiatan</label>
                                <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan"
                                    value="{{ $dataPengajuan->kode_kegiatan }}" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-6">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control form-control-sm" id="nama_user" name="nama_user"
                                value="{{ $dataPengajuan->nama_user }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control form-control-sm" id="kode_user" name="kode_user"
                                value="{{ $dataPengajuan->kode_user }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control form-control-sm" id="kelas" name="kelas"
                                value="{{ $dataPengajuan->kelas }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">total (menit)</label>
                            <input type="text" class="form-control form-control-sm" id="total" name="total"
                                value="{{ $dataPengajuan->total }}" readonly>
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
                                <textarea rows="7" readonly class="form-control" id="nama_pekerjaan"
                                    name="nama_pekerjaan[]">{{ $value->pekerjaan->nama_pekerjaan }}</textarea>
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
                            @if(str_ends_with($value->before_pekerjaan, '.pdf'))
                            <a href="{{ asset('storage/before/'.$value->before_pekerjaan) }}" class="btn btn-primary"
                                download>
                                Download Before Pekerjaan (PDF)
                            </a>
                            @else
                            <a href="{{ asset('storage/before/'.$value->before_pekerjaan) }}"
                                data-lightbox="before-pekerjaan-{{ $value->id }}" data-title="Before Pekerjaan">
                                <img src="{{ asset('storage/before/'.$value->before_pekerjaan) }}"
                                    alt="Before Pekerjaan" style="max-width: 200px; margin-top: 10px;">
                            </a>
                            @endif
                            @else
                            <p>No Before Pekerjaan image available</p>
                            @endif
                        </div>
                        <hr>

                        <!-- After Pekerjaan -->
                        <div class="form-group">
                            <label for="after_pekerjaan">After Pekerjaan</label>
                            @if($value->after_pekerjaan)
                            @if(str_ends_with($value->after_pekerjaan, '.pdf'))
                            <a href="{{ asset('storage/after/'.$value->after_pekerjaan) }}" class="btn btn-primary"
                                download>
                                Download After Pekerjaan (PDF)
                            </a>
                            @else
                            <a href="{{ asset('storage/after/'.$value->after_pekerjaan) }}"
                                data-lightbox="after-pekerjaan-{{ $value->id }}" data-title="After Pekerjaan">
                                <img src="{{ asset('storage/after/'.$value->after_pekerjaan) }}" alt="After Pekerjaan"
                                    style="max-width: 200px; margin-top: 10px;">
                            </a>
                            @endif
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
                            @if(str_ends_with($dataPengajuan->bukti_tambahan, '.pdf'))
                            <a href="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                class="btn btn-primary" download>
                                Download Bukti Tambahan (PDF)
                            </a>
                            @else
                            <a href="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                data-lightbox="bukti-tambahan" data-title="Bukti Tambahan">
                                <img src="{{ asset('storage/bukti_tambahan/'.$dataPengajuan->bukti_tambahan) }}"
                                    alt="Bukti Tambahan" style="max-width: 200px; margin-top: 10px;">
                            </a>
                            @endif
                        </div>
                        @endif
                        <hr>

                        <!-- Status Approval 1 -->
                        <div class="form-group">
                            <label>Status Approval 1</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_approval1"
                                    id="approval1_disetujui" value="Disetujui">
                                <label class="form-check-label" for="approval1_disetujui">Disetujui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_approval1"
                                    id="approval1_ditolak" value="Ditolak">
                                <label class="form-check-label" for="approval1_ditolak">Ditolak</label>
                            </div>
                        </div>

                        <!-- Keterangan Approval 1 -->
                        <div class="form-group">
                            <label for="keterangan_approval1">Keterangan Approval 1</label>
                            <textarea class="form-control" id="keterangan_approval1"
                                name="keterangan_approval1"></textarea>
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
                            <a href="{{ route('pengawas.listpengajuan') }}" class="btn btn-secondary">Back</a>
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