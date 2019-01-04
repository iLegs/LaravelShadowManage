@extends('common')
@section('style')
    <link rel="stylesheet" href="{{ $s }}/css/web/index.css">
@endsection
@section('body')
<div class="container banner">
    <div id="banner" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @php $ii = 0; @endphp
            @foreach($banners as $banner)
                <li data-target="#banner" data-slide-to="{{ $ii }}" @if($ii == 0)class="active"@endif></li>
                @php $ii++; @endphp
            @endforeach
        </ol>
        <div class="carousel-inner">
            @php $ll = 0; @endphp
            @foreach($banners as $banner)
                <div class="carousel-item @if($ll == 0) active @endif">
                    <a href="{{ $banner['url'] }}" target="_blank" alt="{{ $banner['desc'] }}">
                        <img src="{{ $banner['pic'] }}" class="d-block w-100" alt="{{ $banner['desc'] }}">
                        <div class="carousel-caption d-none d-md-block">
                            <h6>{{ $banner['desc'] }}</h6>
                        </div>
                    </a>
                </div>
                @php $ll++; @endphp
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#banner" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#banner" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<div class="container content">
    @foreach($libs as $lib)
        @if($lib['count'] > 0)
            <div class="albums">
                <div class="row album-header">
                    <div class="col-6 text-left">
                        <a class="btn btn-primary icon" target="_blank" href="/lib/{{ $lib['url'] }}/list.html" data-icon="&#xe010;" role="button">&nbsp;{{ $lib['title'] }}
                        </a>
                    </div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary icon" target="_blank" href="/lib/{{ $lib['url'] }}/list.html" data-icon="^" role="button">&nbsp;More
                            <span class="badge badge-warning">{{ $lib['count'] }}</span>
                        </a>
                    </div>
                </div>
                <div class="row content">
                    @foreach($lib['albums'] as $album)
                        <div class="col-6 col-md-3 col-xl-3">
                            <div class="card">
                                <a href="/album/detail/{{ $album['id'] }}.html" target="_blank">
                                    <img class="img-fluid lazyload" src="{{ $s }}/img/cover.png" data-src="{{ $album['cover'] }}" alt="{{ $album['title'] }}">
                                </a>
                                <div class="card-body album-title">
                                    <p class="card-text text-truncate text-center">{{ $album['title'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
@section('script')
    <script src="{{ $s }}/js/lazyload.min.js"></script>
    <script type="text/javascript">
        $("img.lazyload").lazyload();
    </script>
@endsection
