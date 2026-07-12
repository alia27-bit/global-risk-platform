@extends('layouts.app')

@section('title', 'Master Negara')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">Master Negara</h3>
            <small class="text-muted">
                Data negara berasal dari REST Countries API
            </small>
        </div>

        <div class="d-flex gap-2">

            <form action="{{ route('negara.sinkronisasi') }}" method="POST">

                @csrf

                <button type="submit" class="btn btn-success">

                    <i class="bi bi-arrow-repeat"></i>

                    Sinkronisasi API

                </button>

            </form>

            <a href="{{ route('negara.index') }}"
                class="btn btn-primary">

                <i class="bi bi-arrow-clockwise"></i>

                Refresh

            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            Daftar Negara

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">No</th>

                            <th>Bendera</th>

                            <th>Kode</th>

                            <th>Nama Negara</th>

                            <th>Ibu Kota</th>

                            <th>Wilayah</th>

                            <th>Mata Uang</th>

                            <th>Populasi</th>

                            <th width="170">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($negara as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>

                                    @if($item->bendera)

                                        <img src="{{ $item->bendera }}"
                                            width="50">

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    {{ $item->kode_negara }}

                                </td>

                                <td>

                                    {{ $item->nama_negara }}

                                </td>

                                <td>

                                    {{ $item->ibu_kota }}

                                </td>

                                <td>

                                    {{ $item->wilayah }}

                                </td>

                                <td>

                                    {{ $item->mata_uang }}

                                </td>

                                <td>

                                    {{ number_format($item->populasi,0,',','.') }}

                                </td>

                                <td>

                                    <a href="{{ route('negara.show',$item->id) }}"
                                        class="btn btn-info btn-sm">

                                        Detail

                                    </a>

                                    <form action="{{ route('negara.destroy',$item->id) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Hapus negara ini?')"
                                            class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center">

                                    Belum ada data negara.

                                    <br><br>

                                    Klik tombol

                                    <strong>Sinkronisasi API</strong>

                                    untuk mengambil data dari REST Countries.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection