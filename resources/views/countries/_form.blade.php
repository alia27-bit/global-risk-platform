@csrf
<div class="row g-3">
@foreach([['name','Nama Negara','text'],['code','Kode ISO3','text'],['capital','Ibu Kota','text'],['region','Wilayah','text'],['subregion','Subwilayah','text'],['population','Populasi','number'],['latitude','Latitude','number'],['longitude','Longitude','number'],['currency','Nama Mata Uang','text'],['currency_code','Kode Mata Uang','text'],['flag','URL Bendera','url']] as [$field,$label,$type])
<div class="{{ in_array($field,['name','flag']) ? 'col-12' : 'col-md-6' }}"><label class="form-label">{{ $label }}</label><input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, $country->{$field} ?? '') }}" class="form-control @error($field) is-invalid @enderror" {{ in_array($field,['name','code']) ? 'required' : '' }} {{ in_array($field,['latitude','longitude']) ? 'step=any' : '' }}>@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@endforeach
</div><div class="mt-4"><button class="btn btn-primary">Simpan</button><a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">Batal</a></div>
