@extends('common')
@section('style')
<style type="text/css">
    .row .btn {
        margin: .2rem;
        background-color: #9966FF;
        border: none;
    }
</style>
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
