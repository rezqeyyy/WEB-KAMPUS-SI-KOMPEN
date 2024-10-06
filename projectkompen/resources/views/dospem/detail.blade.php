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
                    <div class="card-title">
                        <h4 class="mb-0">Detail Bebas Kompen</h4>
                    </div>
                    <hr>
                    <!-- Form Detail -->
                    <div class="form-group">
                        <label for="id_pengajuan">ID Pengajuan</label>
                        <input type="text" class="form-control" id="id_pengajuan" name="id_pengajuan"
                            value="{{ $dataPengajuan->id_pengajuan }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="kode_user">NIM</label>
                        <input type="text" class="form-control" id="kode_user" name="kode_user"
                            value="{{ $dataPengajuan->kode_user }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_user">Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user"
                            value="{{ $dataPengajuan->nama_user }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas"
                            value="{{ $dataPengajuan->kelas }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi"
                            value="{{ $dataPengajuan->prodi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" id="semester" name="semester"
                            value="{{ $dataPengajuan->semester }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status_approval1">Status Approval 1</label>
                        <input type="text" class="form-control" id="status_approval1" name="status_approval1"
                            value="{{ $dataPengajuan->status_approval1 }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status_approval2">Status Approval 2</label>
                        <input type="text" class="form-control" id="status_approval2" name="status_approval2"
                            value="{{ $dataPengajuan->status_approval2 }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status_approval3">Status Approval 3</label>
                        <input type="text" class="form-control" id="status_approval3" name="status_approval3"
                            value="{{ $dataPengajuan->status_approval3 }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="approval1_by">Approval 1 By</label>
                        <input type="text" class="form-control" id="approval1_by" name="approval1_by"
                            value="{{ $dataPengajuan->approval1_by }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="approval2_by">Approval 2 By</label>
                        <input type="text" class="form-control" id="approval2_by" name="approval2_by"
                            value="{{ $dataPengajuan->approval2_by }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="approval3_by">Approval 3 By</label>
                        <input type="text" class="form-control" id="approval3_by" name="approval3_by"
                            value="{{ $dataPengajuan->approval3_by }}" readonly>
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

                    <!-- Button to go back -->
                    <a href="{{ route('dospem.listbebas') }}" class="btn btn-secondary">Kembali</a>
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