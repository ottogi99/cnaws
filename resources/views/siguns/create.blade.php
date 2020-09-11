@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>시군 항목 생성</h1>
    <hr/>

    <form action="{{ route('siguns.store') }}" method="POST" class="form__siguns">
      {!! csrf_field() !!}

      @include('siguns.partial.form')

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          저장
        </button>
      </div>
    </form>
  </div>
@stop
