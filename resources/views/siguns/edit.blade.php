@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h4>시군<small> / 수정 / {{ $sigun->name }}</small></h4>
    </div>

    <form action="{{ route('siguns.update', $sigun->id) }}" method="POST">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}

        @include('siguns.partial.form')

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                수정하기
            </button>
        </div>
    </form>
@stop
