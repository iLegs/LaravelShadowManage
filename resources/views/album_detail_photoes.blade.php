@extends('common')
@section('style')
    <link rel="stylesheet" href="{{ $s }}/css/web/album_detail.css?v1.1.0">
    <link href="{{ $s }}/lightGallery-1.6.11/lightgallery.min.css" rel="stylesheet">
    <link href="{{ $s }}/sweetalert2-7.32.4/sweetalert2.min.css" rel="stylesheet">
@endsection
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <button type="button" class="btn icon" data-icon="&#xe009;"></button>
                <button type="button" class="btn btn-outline-secondary title" disabled="disabled">专辑名称：{{ $album->title }}</button>
            </div>
            <div class="col-12 col-md-4 right">
                <button type="button" class="btn icon" data-icon="Y"></button>
                <button type="button" class="btn btn-outline-success tz" data-href="#" disabled="disabled">发行时间：{{ $album->date }}</button>
            </div>
            <div class="col-12 col-md-8">
                <button type="button" class="btn icon" data-icon="&#xe057;"></button>
                <button type="button" class="btn btn-outline-secondary" disabled="disabled">模特</button>
                @foreach($album->getModels() as $legmodel)
                <button type="button" class="btn btn-outline-success tz" data-href="#" disabled="disabled">{{ $legmodel['name'] }}</button>
                @endforeach
            </div>
            <div class="col-12 col-md-4 right">
                <button type="button" class="btn icon" data-icon="&#xe037;"></button>
                <button type="button" class="btn btn-outline-secondary" disabled="disabled">浏览次数：{{ $album->browse_times }}</button>
            </div>
            <div class="col-12">
                <button type="button" class="btn icon" data-icon="&#xe04c;"></button>
                <button type="button" class="btn btn-outline-secondary" disabled="disabled">标签</button>
                @foreach($album->getTags() as $tag)
                    <button type="button" class="btn btn-outline-success tz" data-href="#" disabled="disabled">{{ $tag['title'] }}</button>
                @endforeach
            </div>
        </div>
        <div class="row" id="lightgallery">
            @php $ii = 1; @endphp
            @foreach($photoes as $photo)
                <div class="col-6 col-md-3" data-responsive="{{ $photo['preview'] }}" data-src="{{ $photo['original'] }}" data-sub-html="{{ $photo['id']}}">
                    <div class="card">
                        <img class="img-fluid lazyload" src="{{ $s }}/img/image.png" data-src="{{ $photo['preview'] }}" alt="{{ $album->title }}">
                        <div class="card-body">
                            <p class="card-text text-center">{{ $ii }}</p>
                        </div>
                    </div>
                </div>
                @php $ii += 1; @endphp
            @endforeach
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ $s }}/js/lazyload.min.js"></script>
    <script src="{{ $s }}/js/a_common.js"></script>
    <script src="{{ $s }}/js/web_album_detail.js?v1.0"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/picturefill.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/lightgallery-all.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/jquery.mousewheel.min.js"></script>
    <script src="{{ $s }}/sweetalert2-7.32.4/sweetalert2.all.min.js"></script>
@endsection
