@extends('mhsw.layout')

@section('custom-css')
<style>
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Montserrat', sans-serif;
    }

    .card .body {
        font-family: "Bookman Old Style", Georgia, serif;
    }

    #SuratBebasKompen {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    #SuratBebasKompen th,
    #SuratBebasKompen td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    #SuratBebasKompen th {
        background-color: #f2f2f2;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header img {
        width: 120px;
        height: auto;
    }

    .header-text h1,
    .header-text h2,
    .header-text p {
        margin: 0;
    }

    .header-text h1 {
        font-size: 20px;
        font-weight: bold;
    }

    .header-text h2 {
        font-size: 18px;
        font-weight: bold;
    }

    .header-text p {
        font-size: 12px;
    }

    .content {
        margin: 0 50px;
        font-size: 14px;
    }

    .content .date {
        text-align: right;
    }

    .content .body {
        margin-top: 20px;
        line-height: 1.6;
    }

    .signature-section {
        margin-top: 30px;
    }

    .signature-box {
        margin-bottom: 20px;
    }

    .table-title {
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
    }

    .table-wrapper {
        margin-top: 10px;
    }

    /* CSS for printing */
    @media print {
        body * {
            visibility: hidden;
        }

        .card,
        .card * {
            visibility: visible;
        }

        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
    }
</style>
@endsection

@section('title', 'Detail Pengajuan')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Detail Pengajuan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('mahasiswa.pekerjaan.listdiambil') }}">List Diambil</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Surat Bebas Kompen</li>
            </ol>
        </nav>
    </div>
</div>
<div class="toolbar hidden-print">
    <div class="text-right">
        <button type="button" class="btn btn-primary btn-block" id="print-btn">
            Export as PDF
        </button>
    </div>
    <hr />
</div>

<div class="card">
    <div class="card-body">
        <div id="SuratBebasKompen" style="width: 100%; height: 100%">
            <div class="SuratBebasKompen overflow-auto">
                <div style="min-width: 600px">
                    <header class="header">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 10%; text-align: center;">
                                    <img src="{{asset('assets/images/Logo_Politeknik_Negeri_Jakarta-removebg-preview.png')}}"
                                        alt="Logo">
                                </td>
                                <td style="width: 70%; text-align: center;">
                                    <div class="header-text">
                                        <h1>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h1>
                                        <h2>POLITEKNIK NEGERI JAKARTA</h2>
                                        <h2>JURUSAN TEKNIK INFORMATIKA DAN KOMPUTER</h2>
                                    </div>
                                </td>
                                <td style="width: 10%; text-align: center; font-weight: bold; font-size: 16px;">
                                    <span style="font-size: 24px;">K-01</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; font-weight: bold;">LEMBAR BEBAS PINJAMAN,
                                    ADMINISTRASI DAN KOMPENSASI</td>
                            </tr>
                        </table>
                    </header>
                    <main class="content">
                        <div class="body">
                            <p><strong>NAMA MAHASISWA</strong> : {{ $dataPengajuan->nama_user }}</p>
                            <p><strong>NIM</strong> : {{ $dataPengajuan->kode_user }}</p>
                            <p><strong>PRODI</strong> : {{ $dataPengajuan->prodi }}</p>
                            <p><strong>KELAS</strong> : {{ $dataPengajuan->kelas }}</p>

                            <div class="table-wrapper">
                                <div class="table-title">BEBAS PEMINJAMAN ALAT</div>
                                <table id="SuratBebasKompen">
                                    <tr>
                                        <th style="text-align: center;">NO.</th>
                                        <th style="text-align: center;">URAIAN</th>
                                        <th style="text-align: center;">TGL</th>
                                        <th style="text-align: center;">Tanda Tangan Laboran</th>
                                        <th style="text-align: center;">Tanda Tangan Ka./Pennanggung Jawab</th>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">1</td>
                                        <td>LAB. JARINGAN DAN KOMPUTER</td>
                                        <td>
                                            <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                        </td>
                                        <td style="text-align: center;"> <img src="{{ $ttd2 }}" width="100" height="50"
                                                alt="PLP" /></td>
                                        <td style="text-align: center;"> <img src="{{ $ttd3 }}" width="100" height="50"
                                                alt="Kepala Lab" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">2</td>
                                        <td>LAB. CYBER SECURITY</td>
                                        <td>
                                            <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                        </td>
                                        <td style="text-align: center;"> <img src="{{ $ttd2 }}" width="100" height="50"
                                                alt="PLP" /></td>
                                        <td style="text-align: center;"> <img src="{{ $ttd3 }}" width="100" height="50"
                                                alt="Kepala Lab" /></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="table-wrapper">
                                <div class="table-title">BEBAS ADMINISTRASI</div>
                                <table id="SuratBebasKompen">
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">URAIAN</th>
                                        <th style="text-align: center;">TGL</th>
                                        <th style="text-align: center;">Tanda Tangan Pustakawan</th>
                                        <th style="text-align: center;">Tanda Tangan Ka./Penanggung Jawab</th>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">1</td>
                                        <td>PERPUSTAKAAN JURUSAN TIK</td>
                                        <td>
                                            <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">2</td>
                                        <td>PERPUSTAKAAN POLITEKNIK</td>
                                        <td>
                                            <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="table-wrapper">
                                <div class="table-title">KOMPENSASI</div>
                                <table id="SuratBebasKompen">
                                    <tr>
                                        <th style="text-align: center;">URAIAN</th>
                                        <th style="text-align: center;">JUMLAH</th>
                                        <th style="text-align: center;">Tanda tangan Pengawas Kompen</th>
                                        <th style="text-align: center;">Tanda Tangan Koord.Kompensasi</th>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Kompensasi</td>
                                        <td>{{ $dataPengajuan->total }} Menit</td>
                                        <td style="text-align: center;"> <img src="{{ $ttd1 }}" width="100" height="50"
                                                alt="Pengawas" /></td>
                                        <td style="text-align: center;"> <img src="{{ $ttd3 }}" width="100" height="50"
                                                alt="Kepala Lab" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('print-btn').addEventListener('click', function () {
        const element = document.querySelector('.card');
        console.log(element); // Check if the selector works correctly
        const opt = {
            filename: `SuratBebasKompen-{{ $dataPengajuan->nama_user }}-{{ $dataPengajuan->kode_user }}-{{ $dataPengajuan->kelas }}.pdf`,
        };
        html2pdf().from(element).set(opt).save();
    });
</script>

@endsection
