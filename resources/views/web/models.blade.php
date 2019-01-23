@extends('common')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ $s }}/css/web/models.css">
@endsection
@section('body')
    <div class="container">
        <div class="row">
            @foreach($models as $mdl)
                <a class="btn btn-success" href="/model/{{ $mdl['id'] }}.html" role="button">{{ $mdl['name'] }}
                    <span class="badge badge-warning">{{ $mdl['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
