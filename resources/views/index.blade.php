@extends('common')
@section('style')
    <link rel="stylesheet" href="{{ $s }}/Nivo-Slider/themes/default/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{{ $s }}/Nivo-Slider/themes/bar/bar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{{ $s }}/css/web/index.css?v1.1.1">
    <link rel="stylesheet" href="{{ $s }}/Nivo-Slider/nivo-slider.css">
@endsection
@section('body')
<div class="container banner d-lg-block d-none">
    <div id="wrapper">
        <div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
                @php $ii = 0; @endphp
                @foreach($banners as $banner)
                    <a href="{{ $banner['url'] }}" target="_blank">
                        <img src="{{ $banner['pic'] }}" data-thumb="{{ $banner['pic'] }}" alt="{{ $banner['desc'] }}" title="{{ $banner['desc'] }}"/>
                    </a>
                @endforeach
                @php $ii++; @endphp
            </div>
        </div>
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
    <script src="{{ $s }}/Nivo-Slider/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#slider").nivoSlider({
                effect: "random",
                animSpeed: 2000,
                pauseTime: 5000,
            });

            $("img.lazyload").lazyload();
        });
    </script>
@endsection
