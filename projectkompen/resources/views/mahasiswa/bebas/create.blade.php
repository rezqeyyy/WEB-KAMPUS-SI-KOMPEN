@extends('mhsw.layout')

@section('title', 'Form Bebas Kompen')

@section('content')
<div class="container mt-4 d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card radius-15 shadow-sm">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="mb-0">Pengajuan Bebas Kompen</h4>
                </div>
                <hr>
                <form id="form-bebas-kompen" action="{{ route('mahasiswa.bebas.create.proses') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="id_pengajuan">ID Pengajuan</label>
                        <select class="form-control" id="id_pengajuan" name="id_pengajuan">
                            <option value="">Pilih ID Pengajuan</option>
                            @foreach ($pengajuan as $p)
                            <option value="{{ $p->id_pengajuan }}">{{ $p->id_pengajuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="status_approval1" id="status_approval1">
                    <input type="hidden" name="status_approval2" id="status_approval2">
                    <input type="hidden" name="status_approval3" id="status_approval3">
                    <input type="hidden" name="approva1_by" id="approva1_by">
                    <input type="hidden" name="approva2_by" id="approva2_by">
                    <input type="hidden" name="approva3_by" id="approva3_by">

                    <div class="form-group">
                        <label for="kelas">NIM</label>
                        <input type="text" class="form-control" id="kode_mahasiswa" name="kode_mahasiswa" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kelas">Nama</label>
                        <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" readonly>
                    </div>

                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" readonly>
                    </div>
                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi" readonly>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" id="semester" name="semester" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_terlambat">Jumlah Terlambat</label>
                        <input type="text" class="form-control" id="jumlah_terlambat" name="jumlah_terlambat" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_alfa">Jumlah Alfa</label>
                        <input type="text" class="form-control" id="jumlah_alfa" name="jumlah_alfa" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" class="form-control" id="total" name="total" readonly>
                    </div>
                    <div class="form-group">
                        <label for="sisa">Sisa</label>
                        <input type="text" class="form-control" id="sisa" name="sisa" readonly>
                    </div>
                    <div class="form-group">
                        <label for="form_bebas_kompen">Upload Form Bebas Kompen</label>
                        <input type="file" class="form-control-file" id="form_bebas_kompen" name="form_bebas_kompen">
                        <img id="image-preview" src="#" alt="Preview Image"
                            style="display:none; max-width:100%; margin-top:10px;">
                    </div>
                    <br>
                    <div class="form-group text-right">
                        <button type="button" id="submit-button" class="btn btn-primary">Submit</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#id_pengajuan').on('change', function() {
            var id_pengajuan = $(this).val();
            if (id_pengajuan) {
                $.ajax({
                    // url: '/projectkompen/mahasiswa/bebas/get-pengajuan/' + id_pengajuan,
                    url: '/mahasiswa/bebas/get-pengajuan/' + id_pengajuan,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kode_mahasiswa').val(data.kode_user);
                        $('#nama_mahasiswa').val(data.nama_user);
                        $('#kelas').val(data.kelas);
                        $('#prodi').val(data.prodi);
                        $('#semester').val(data.semester);
                        $('#status_approval1').val(data.status_approval1);
                        $('#status_approval2').val(data.status_approval2);
                        $('#status_approval3').val(data.status_approval3);
                        $('#approva1_by').val(data.approval1_by);
                        $('#approva2_by').val(data.approval2_by);
                        $('#approva3_by').val(data.approval3_by);
                        $('#jumlah_terlambat').val(data.jumlah_terlambat); // Populate jumlah_terlambat
                        $('#jumlah_alfa').val(data.jumlah_alfa); // Populate jumlah_alfa
                        $('#total').val(data.total); // Populate total
                        $('#sisa').val(data.sisa); // Populate sisa
                    }
                });
            } else {
                $('#kode_mahasiswa').val('');
                $('#nama_mahasiswa').val('');
                $('#kelas').val('');
                $('#prodi').val('');
                $('#semester').val('');
                $('#status_approval1').val('');
                $('#status_approval2').val('');
                $('#status_approval3').val('');
                $('#approva1_by').val('');
                $('#approva2_by').val('');
                $('#approva3_by').val('');
                $('#jumlah_terlambat').val(''); // Clear jumlah_terlambat
                $('#jumlah_alfa').val(''); // Clear jumlah_alfa
                $('#total').val(''); // Clear total
                $('#sisa').val(''); // Clear sisa
            }
        });

        $('#form_bebas_kompen').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview').show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#submit-button').on('click', function() {
            var idPengajuan = $('#id_pengajuan').val();
            var fileInput = $('#form_bebas_kompen').val();

            if (!idPengajuan) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID Pengajuan harus dipilih!'
                });
                return;
            }

            if (!fileInput) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Form Bebas Kompen harus diupload!'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-bebas-kompen').submit();
                }
            })
        });
    });
</script>
@endsection