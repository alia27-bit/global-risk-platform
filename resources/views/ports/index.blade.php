@extends('layouts.app')
@section('title','Pelabuhan')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h3>Port Location Dashboard</h3><p class="text-muted mb-0">World Port Index dan lokasi pelabuhan dunia.</p></div><div class="d-flex gap-2"><a href="{{ route('ports.map') }}" class="btn btn-success"><i class="bi bi-map"></i> Peta</a>@if(auth()->user()->peran==='admin')<a href="{{ route('ports.sync') }}" class="btn btn-warning"><i class="bi bi-cloud-download"></i> Sinkronkan WPI</a><a href="{{ route('ports.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>@endif</div></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="info-card"><div class="card-body">
        <input id="portSearch" class="form-control mb-3" placeholder="Cari pelabuhan atau negara...">
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Nama</th><th>Negara</th><th>Latitude</th><th>Longitude</th><th>Aksi</th></tr></thead><tbody id="portRows">
        @forelse($ports as $port)<tr class="port-row" data-search="{{ strtolower($port->name.' '.$port->country?->name) }}"><td class="fw-semibold">{{ $port->name }}</td><td>{{ $port->country?->name }}</td><td>{{ $port->latitude }}</td><td>{{ $port->longitude }}</td><td><a href="{{ route('ports.show',$port) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>@if(auth()->user()->peran==='admin') <a href="{{ route('ports.edit',$port) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a><form class="d-inline" method="POST" action="{{ route('ports.destroy',$port) }}" onsubmit="return confirm('Hapus pelabuhan?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endif</td></tr>
        @empty<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada dataset pelabuhan.</td></tr>@endforelse
        </tbody></table></div>{{ $ports->links() }}
    </div></div>
</div>
@endsection
@push('scripts')
<script>
let portTimer;
document.getElementById('portSearch').addEventListener('input',function(){
    clearTimeout(portTimer); const keyword=this.value.trim();
    portTimer=setTimeout(async()=>{
        if(keyword.length<2){document.querySelectorAll('.port-row').forEach(row=>row.classList.toggle('d-none',!row.dataset.search.includes(keyword.toLowerCase())));return;}
        const response=await fetch(@json(route('ports.search'))+'?keyword='+encodeURIComponent(keyword),{headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'}});
        const ports=await response.json();
        document.getElementById('portRows').innerHTML=ports.length?ports.map(port=>`<tr><td class="fw-semibold">${port.name}</td><td>${port.country?.name??'-'}</td><td>${port.latitude??'-'}</td><td>${port.longitude??'-'}</td><td><a class="btn btn-sm btn-outline-primary" href="${port.url}"><i class="bi bi-eye"></i></a></td></tr>`).join(''):'<tr><td colspan="5" class="text-center py-4">Tidak ditemukan.</td></tr>';
    },300);
});
</script>
@endpush
