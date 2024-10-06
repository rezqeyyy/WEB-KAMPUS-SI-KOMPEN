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
                    <h4>Approval PLP</h4>
                </div>
                <div class="card-body">
                    <form id="approval-form" method="POST"
                        action="{{ route('plp.edit.proses', ['kode_kegiatan' => $dataPengajuan->kode_kegiatan]) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="oldid" value="{{ $dataPengajuan->kode_kegiatan }}">
                        <input type="hidden" name="approval2_by" id="approval2_by" value="{{ $nama_user }}">

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
                                <label for="kode_user">Kode User</label>
                                <input type="text" class="form-control" id="kode_user" name="kode_user"
                                    value="{{ $dataPengajuan->kode_user }}" readonly>
                            </div>
                        </div>
                        <!-- Kode User -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_user">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas"
                                    value="{{ $dataPengajuan->kelas }}" readonly>
                            </div>
                        </div>
                        <!-- Kode Kegiatan -->
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="kode_kegiatan">Kode Kegiatan</label>
                                <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan"
                                    value="{{ $dataPengajuan->kode_kegiatan }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="kode_kegiatan">Total (menit)</label>
                                <input type="text" class="form-control" id="total" name="total"
                                    value="{{ $dataPengajuan->total }}" readonly>
                            </div>
                        </div>
                        <br>

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
                        <div class="form-group">
                            <label>Status Approval 2</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_approval2"
                                    id="approval2_disetujui" value="Disetujui">
                                <label class="form-check-label" for="approval2_disetujui">Disetujui</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="submit-button">Submit</button>
                            <a href="{{ route('plp.listpengajuan') }}" class="btn btn-secondary">Back</a>
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
        Swal.fire({
            title: 'Apakah anda yakin akan menyimpan perubahan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approval-form').submit();
            }
        });
    });
</script>
@endsection