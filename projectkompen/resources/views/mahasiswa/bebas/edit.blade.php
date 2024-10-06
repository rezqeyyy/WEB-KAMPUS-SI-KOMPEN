@extends('mhsw.layout')

@section('title', 'Edit Form Bebas Kompen')

@section('content')
<div class="container mt-4 d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card radius-15 shadow-sm">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="mb-0">Edit Form Bebas Kompen</h4>
                </div>
                <hr>
                <form id="form-bebas-kompen"
                    action="{{ route('mahasiswa.bebas.edit.proses', $dataPengajuan->id_bebas_kompen) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="id_pengajuan">ID Pengajuan</label>
                        <select class="form-control" id="id_pengajuan" name="id_pengajuan">
                            @foreach ($pengajuan as $p)
                            <option value="{{ $p->id_pengajuan }}" {{ $dataPengajuan->id_pengajuan ==
                                $p->id_pengajuan ? 'selected' : '' }}>{{ $p->id_pengajuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="status_approval1" id="status_approval1"
                        value="{{ $dataPengajuan->status_approval1 }}">
                    <input type="hidden" name="status_approval2" id="status_approval2"
                        value="{{ $dataPengajuan->status_approval2 }}">
                    <input type="hidden" name="status_approval3" id="status_approval3"
                        value="{{ $dataPengajuan->status_approval3 }}">
                    <input type="hidden" name="approva1_by" id="approva1_by" value="{{ $dataPengajuan->approval1_by }}">
                    <input type="hidden" name="approva2_by" id="approva2_by" value="{{ $dataPengajuan->approval2_by }}">
                    <input type="hidden" name="approva3_by" id="approva3_by" value="{{ $dataPengajuan->approval3_by }}">

                    <div class="form-group">
                        <label for="kode_user">NIM</label>
                        <input type="text" class="form-control" id="kode_user" name="kode_user"
                            value="{{ $dataPengajuan->kode_user }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_user">Nama</label>
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
                        <label for="jumlah_terlambat">Jumlah Terlambat (Menit)</label>
                        <input type="text" class="form-control" id="jumlah_terlambat" name="jumlah_terlambat"
                            value="{{ $dataPengajuan->jumlah_terlambat }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_alfa">Jumlah Alfa (Menit)</label>
                        <input type="text" class="form-control" id="jumlah_alfa" name="jumlah_alfa"
                            value="{{ $dataPengajuan->jumlah_alfa }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" class="form-control" id="total" name="total"
                            value="{{ $dataPengajuan->total }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="sisa">Sisa</label>
                        <input type="text" class="form-control" id="sisa" name="sisa" value="{{ $dataPengajuan->sisa }}"
                            readonly>
                    </div>

                    {{-- <div class="form-group">
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
                        <label for="approva1_by">Approval 1 By</label>
                        <input type="text" class="form-control" id="approva1_by" name="approva1_by"
                            value="{{ $dataPengajuan->approval1_by }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="approva2_by">Approval 2 By</label>
                        <input type="text" class="form-control" id="approva2_by" name="approva2_by"
                            value="{{ $dataPengajuan->approval2_by }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="approva3_by">Approval 3 By</label>
                        <input type="text" class="form-control" id="approva3_by" name="approva3_by"
                            value="{{ $dataPengajuan->approval3_by }}" readonly>
                    </div> --}}

                    <div class="form-group">
                        <label for="form_bebas_kompen">Upload Form Bebas Kompen</label>
                        <input type="file" class="form-control-file" id="form_bebas_kompen" name="form_bebas_kompen">
                        <img id="image-preview"
                            src="{{ asset('storage/form_bebas_kompen/'.$dataPengajuan->form_bebas_kompen) }}"
                            alt="Preview Image" style="max-width:100%; margin-top:10px;">
                    </div>
                    <br>

                    <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
                    <a href="{{ route('mahasiswa.bebas.listbebas') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js-content')
<script>
    $(document).ready(function() {
        $('#id_pengajuan').on('change', function() {
            var id_pengajuan = $(this).val();
            if (id_pengajuan) {
                $.ajax({
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

        $('#form-bebas-kompen').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

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
                    this.submit(); // Submit the form programmatically
                }
            });
        });
    });
</script>
@endsection