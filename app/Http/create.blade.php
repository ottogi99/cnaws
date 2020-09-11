@extends('layouts.app')

@section('content')
  @php $viewName = 'users.create'; @endphp

  <div class="container">
    <h1>사용자(항목) 등록</h1>
    <hr/>

    <form action="{{ route('users.store') }}" method="POST" class="form__siguns">
      @csrf

      @include('users.partial.form')

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          저장
        </button>
      </div>
    </form>
  </div>
@stop
