@extends('super.master-layout')

@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
@endsection

@section('title', 'Detail Bebas Kompen')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card radius-15">
                <div class="card-body">
                    <h4 class="card-title mb-4">Detail Bebas Kompen</h4>
                    <hr>

                    <form>
                        <div class="form-group">
                            <label>ID Pengajuan</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->id_pengajuan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->kode_user }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Mahasiswa</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->nama_user }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->kelas }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Program Studi</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->prodi }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->semester }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Terlambat (menit)</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->jumlah_terlambat }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Alfa (menit)</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->jumlah_alfa }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Total</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->total }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Approval 1 By</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->approval1_by }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Approval 2 By</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->approval2_by }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Approval 3 By</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->approval3_by }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Form Bebas Kompen</label><br>
                            <!-- Thumbnail Image with Lightbox -->
                            <a href="{{ asset('storage/form_bebas_kompen/'.$dataPengajuan->form_bebas_kompen) }}"
                                data-lightbox="form-bebas-kompen" data-title="Form Bebas Kompen">
                                <img src="{{ asset('storage/form_bebas_kompen/'.$dataPengajuan->form_bebas_kompen) }}"
                                    alt="Form Bebas Kompen" class="img-thumbnail" style="max-width: 200px;">
                            </a>
                        </div>
                        <br>
                        <!-- Button to go back -->
                        <a href="{{ route('kajur.listmahasiswa') }}" class="btn btn-secondary">Kembali</a>
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
    $(document).ready(function() {
        // Initialize Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });
    });
</script>
@endsection