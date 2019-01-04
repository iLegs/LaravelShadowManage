@extends('common')
@section('style')
    <link rel="stylesheet" href="{{ $s }}/dropload/dropload.css">
    <link rel="stylesheet" href="{{ $s }}/css/web/lists.css">
@endsection
@section('body')
<div class="container">
    <div class="row" id="albums" data-url="/lib/{{ $active }}/list.html">
        @foreach($albums as $album)
            <div class="col-6 col-md-3 col-xl-3">
                <div class="card">
                    <a href="/album/detail/{{ $album['id'] }}.html">
                        <img class="img-fluid" src="{{ $album['cover'] }}" alt="{{ $album['title'] }}">
                    </a>
                    <div class="card-body album-title">
                        <p class="card-text text-truncate text-center">{{ $album['title'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="container" id="dropload"></div>
@endsection
@section('script')
    <script src="{{ $s }}/dropload/dropload.min.js?v=v1.0"></script>
    <script src="{{ $s }}/js/web_albums.js?v1.0"></script>
@endsection
