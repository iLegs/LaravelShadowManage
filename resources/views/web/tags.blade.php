@extends('common')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ $s }}/css/web/tags.css">
@endsection
@section('body')
    <div class="container">
        <div class="row">
            @foreach($tags as $tag)
                <a class="btn btn-success" href="/tag/{{ $tag['id'] }}.html" role="button">{{ $tag['title'] }}
                    <span class="badge badge-warning">{{ $tag['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
