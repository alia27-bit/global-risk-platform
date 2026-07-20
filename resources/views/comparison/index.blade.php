@extends('layouts.app')
@section('title','Country Comparison')
@section('content')
<div class="container-fluid"><div class="mb-4"><h3 class="mb-1">Country Comparison Engine</h3><p class="text-muted mb-0">Bandingkan indikator ekonomi, cuaca, mata uang, dan risiko dua negara.</p></div>
<div class="info-card"><div class="card-header-navy"><i class="bi bi-columns-gap"></i>Pilih Negara</div><div class="card-body p-4"><form method="POST" action="{{ route('comparison.compare') }}">@csrf
    <div class="row g-4 align-items-end"><div class="col-md-5"><label class="form-label">Negara Pertama</label><select name="country_a" class="form-select @error('country_a') is-invalid @enderror" required><option value="">Pilih negara...</option>@foreach($countries as $country)<option value="{{ $country->id }}" @selected(old('country_a') == $country->id)>{{ $country->name }} ({{ $country->code }})</option>@endforeach</select>@error('country_a')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-2 text-center"><div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px"><strong>VS</strong></div></div>
    <div class="col-md-5"><label class="form-label">Negara Kedua</label><select name="country_b" class="form-select @error('country_b') is-invalid @enderror" required><option value="">Pilih negara...</option>@foreach($countries as $country)<option value="{{ $country->id }}" @selected(old('country_b') == $country->id)>{{ $country->name }} ({{ $country->code }})</option>@endforeach</select>@error('country_b')<div class="invalid-feedback">{{ $message }}</div>@enderror</div></div>
    <div class="text-center mt-4"><button class="btn btn-primary px-5"><i class="bi bi-bar-chart me-1"></i>Bandingkan</button></div>
</form></div></div></div>
@endsection
