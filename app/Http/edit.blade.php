@extends('layouts.app')

@section('content')
  @php $viewName = 'users.edit'; @endphp

  <div class="page-header">
    <h4>사용자(농협)<small> / 수정 / {{ $user->user_id }}</small></h4>
  </div>

  <form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    {!! method_field('PUT') !!}

    @include('users.partial.form', $siguns)

    <div class="form-group">
      <button type="submit" class="btn btn-primary">
        수정하기
      </button>
    </div>
  </form>
@stop
