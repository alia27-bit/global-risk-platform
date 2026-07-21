@csrf
<div class="mb-3">
    <label class="form-label">Negara</label>
    <select name="country_id" class="form-select" required>
        <option value="">Pilih...</option>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}" @selected(old('country_id', $port->country_id ?? null) == $country->id)>{{ $country->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Nama Pelabuhan</label>
    <input name="name" value="{{ old('name', $port->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Jenis Pelabuhan</label>
    <input name="port_type" value="{{ old('port_type', $port->port_type ?? '') }}" class="form-control" placeholder="Contoh: Very Large, Large, Medium">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Latitude</label>
        <input type="number" step="any" name="latitude" value="{{ old('latitude', $port->latitude ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Longitude</label>
        <input type="number" step="any" name="longitude" value="{{ old('longitude', $port->longitude ?? '') }}" class="form-control">
    </div>
</div>
<button class="btn btn-primary">Simpan</button>
<a href="{{ route('ports.index') }}" class="btn btn-outline-secondary">Batal</a>
