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
            @foreach($models as $mdl)
                <a class="btn btn-success" href="/model/{{ $mdl['id'] }}.html" role="button">{{ $mdl['name'] }}
                    <span class="badge badge-warning">{{ $mdl['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
