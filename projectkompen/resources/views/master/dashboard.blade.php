@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title')
@endsection

@section('content')

<div class="row">
    <div class="col-20 col-lg-6">
        <a href="{{ route('master.pekerjaan.listpekerjaan') }}">
            <div class="card radius-15 bg-info mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalPekerjaan }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-info"><i class="bx bx-package text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total Pekerjaan</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-20 col-lg-6">
        <a href="{{ route('master.mahasiswa.listmahasiswa') }}">
            <div class="card radius-15 bg-warning mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalMahasiswa }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-warning"><i class="bx bx-user text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total Mahasiswa</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-20 col-lg-6">
        <a href="{{ route('master.user.listuser') }}">
            <div class="card radius-15 bg-secondary mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalUser }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-secondary"><i class="bx bx-user-circle text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total User</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    {{-- <div class="col-20 col-lg-6">
        <a href="{{ route('master.kelas.listkelas') }}">
            <div class="card radius-15 bg-success mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalKelas }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-success"><i class="bx bx-book-reader text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total Kelas</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-20 col-lg-6">
        <a href="{{ route('master.prodi.listprodi') }}">
            <div class="card radius-15 bg-primary mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalProdi }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-primary"><i class="bx bx-book-reader text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total Prodi</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}
    <div class="col-20 col-lg-6">
        <a href="{{ route('master.setup.listsetup') }}">
            <div class="card radius-15 bg-primary mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h1 class="mb-0 text-white">{{ $totalSetup }}</h1>
                        </div>
                        <div class="ms-auto font-30 text-secondary"><i class="bx bx-cog text-white"></i></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-white">Total Setup</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection

@section('custom-js')
@endsection