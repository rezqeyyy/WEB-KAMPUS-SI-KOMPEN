@extends('super.master-layout')

@section('title', 'List Bebas Kompen')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="mb-0">List Bebas Kompen</h4>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="filter-class">Kelas:</label>
                            <select id="filter-class" class="form-control select2">
                                <option value="">Semua</option>
                                @foreach ($kelasOptions as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-prodi">Program Studi:</label>
                            <select id="filter-prodi" class="form-control select2">
                                <option value="">Semua</option>
                                @foreach ($prodiOptions as $prodi)
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-semester">Semester:</label>
                            <select id="filter-semester" class="form-control select2">
                                <option value="">Semua</option>
                                @foreach ($semesterOptions as $semester)
                                <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-year">Tahun:</label>
                            <select id="filter-year" class="form-control select2">
                                <option value="">Semua</option>
                                @foreach ($yearOptions as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-status">Status:</label>
                            <select id="filter-status" class="form-control select2">
                                <option value="">Semua</option>
                                <option value="Kompen">Kompen</option>
                                <option value="Tidak Kompen">Tidak Kompen</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="filter-button" class="btn btn-primary">Filter</button>
                            <button id="clear-filter-button" class="btn btn-secondary ml-2">Clear</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table-utama" class="table table-sm table-striped table-bordered table-hover"
                            style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pengajuan</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Kelas</th>
                                    <th>Program Studi</th>
                                    <th>Semester</th>
                                    <th>Tahun</th>
                                    <th>Jumlah Terlambat (Menit)</th>
                                    <th>Jumlah Alfa (Menit)</th>
                                    <th>Total (Menit)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPengajuan as $index => $pengajuan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pengajuan->id_pengajuan }}</td>
                                    <td>{{ $pengajuan->kode_user }}</td>
                                    <td>{{ $pengajuan->nama_user }}</td>
                                    <td>{{ $pengajuan->kelas }}</td>
                                    <td>{{ $pengajuan->prodi }}</td>
                                    <td>{{ $pengajuan->semester }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->year }}</td>
                                    <td>{{ $pengajuan->jumlah_terlambat }}</td>
                                    <td>{{ $pengajuan->jumlah_alfa }}</td>
                                    <td>{{ $pengajuan->total }}</td>
                                    <td>
                                        @if ($pengajuan->total == 0)
                                        Tidak Kompen
                                        @elseif ($pengajuan->total > 0)
                                        Kompen
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dospem.detail', $pengajuan->id_bebas_kompen) }}"
                                            class="btn btn-primary btn-sm">Lihat Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card radius-15">
                                    <div class="card-body">
                                        <h4 class="mb-3">Grafik Kompen vs Tidak Kompen per Semester</h4>
                                        <canvas id="chartKompen" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<!-- Include Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 on filter elements
        $('.select2').select2();

        var table = $('#table-utama').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'excel',
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 10] // Menentukan kolom yang akan di-export ke PDF
                    }
                },
                {
                    extend: 'print',
                    autoPrint: true,
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 10] // Menentukan kolom yang akan di-print
                    }
                }
            ],
            order: [[0, 'asc']], // Sort by the first column (No)
        });

        $('#filter-button').on('click', function() {
            var classFilter = $('#filter-class').val();
            var prodiFilter = $('#filter-prodi').val();
            var semesterFilter = $('#filter-semester').val();
            var yearFilter = $('#filter-year').val();
            var statusFilter = $('#filter-status').val();

            table.column(4).search(classFilter).draw(); // Filter by class (index kolom ke-4)
            table.column(5).search(prodiFilter).draw(); // Filter by program studi (index kolom ke-5)
            table.column(6).search(semesterFilter).draw(); // Filter by semester (index kolom ke-6)
            table.column(7).search(yearFilter).draw(); // Filter by year (index kolom ke-7)

            if (statusFilter === 'Kompen') {
                table.column(11).search('^Kompen$', true, false).draw(); // Hanya mencocokkan status Kompen
            } else if (statusFilter === 'Tidak Kompen') {
                table.column(11).search('^Tidak Kompen$', true, false).draw(); // Hanya mencocokkan status Tidak Kompen
            } else {
                table.column(11).search('').draw(); // Hapus filter status jika 'Semua'
            }
        });

        $('#clear-filter-button').on('click', function() {
            $('#filter-class').val('').trigger('change');
            $('#filter-prodi').val('').trigger('change');
            $('#filter-semester').val('').trigger('change');
            $('#filter-year').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            table.search('').columns().search('').draw();
        });

        // Panggil fungsi untuk mengambil data grafik dari controller
        $.ajax({
            url: "{{ route('dospem.generateChartData') }}", // Ganti dengan route yang sesuai di Laravel Anda
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var ctx = document.getElementById('chartKompen').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.semester,
                        datasets: [{
                            label: 'Tidak Kompen',
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            data: data.tidak_kompen
                        }, {
                            label: 'Kompen',
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            data: data.kompen
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1 // Adjust this step size based on your data
                                }
                            }]
                        }
                    }
                });
            }
        });
    });
</script>
@endsection