@extends('layouts.app')
@section('title','Tambah Berita')
@section('content')<div class="container-fluid"><div class="info-card"><div class="card-header-navy"><i class="bi bi-plus-circle"></i>Tambah Berita</div><div class="card-body"><form method="POST" action="{{ route('news.store') }}">@include('news._form')</form></div></div></div>@endsection
