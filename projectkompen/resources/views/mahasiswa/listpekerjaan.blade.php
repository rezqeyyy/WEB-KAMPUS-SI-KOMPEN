@extends('mhsw.layout')
@section('custom-css')
<style>
    .hidden-column {
        display: none;
    }
</style>
@endsection

@section('title', 'List pekerjaan')
@section('content')
<div class="container-fluid mt-4">



    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">List pekerjaan</h4>
                    </div>
                    <hr>

                    </table>
                    <div class="table-responsive">
                        <table id="table-utama"
                            class="table table-sm table-striped table-bordered table-border table-hover"
                            style="width:100%">
                            <thead class="th-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Kegiatan</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Jam Pekerjaan</th>
                                    <th>Sisa Pekerja</th>
                                    <th>Penanggung Jawab</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalData = count($dataPekerjaan);
                                @endphp
                                @foreach ($dataPekerjaan as $nomor => $value)
                                <tr>
                                    <td>{{ $totalData - $nomor }}</td>
                                    <td>{{ $value['kode_pekerjaan'] }}</td>
                                    <td class="text-wrap mw-100">{{ $value['nama_pekerjaan'] }}</td>
                                    <td>{{ $value['jam_pekerjaan'] }}</td>
                                    <td>{{ $value['batas_pekerja'] }}</td>
                                    <td>{{ $value['penanggung_jawab'] }}</td>



                    </div>
                </div>
            </div>

            </td>

            </tr>
            @endforeach
            </tbody>
            </table>
        </div>
        </nav>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection

@section('js-content')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables pada tabel
        $('#table-utama').DataTable({
            dom: 'lfrtip',
            order: [3, 'desc'] // Sort by the index
        });
    });
</script>

@if (Session::has('alert-success'))
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
                icon: 'success',
                title: '{{ Session::get('alert-success') }}'
            });
</script>
@endif
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
@section('css-content')
@endsection