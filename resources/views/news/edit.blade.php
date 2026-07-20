@extends('layouts.app')
@section('title','Edit Berita')
@section('content')<div class="container-fluid"><div class="info-card"><div class="card-header-navy"><i class="bi bi-pencil"></i>Edit Berita</div><div class="card-body"><form method="POST" action="{{ route('news.update', $news) }}">@method('PUT') @include('news._form')</form></div></div></div>@endsection
