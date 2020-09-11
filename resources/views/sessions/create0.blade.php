@extends('layouts.app')

@section('content')
  <form action="{{ route('sessions.store') }}" method="POST" role="form" class="form__auth">
    @csrf

    <div class="page-header">
      <h4>
        로그인
      </h4>
    </div>

    <div class="login-or">
      <hr class="hr-or">
    </div>

    <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
      <input type="nonghyup_id" name="nonghyup_id" class="form-control" placeholder="사용자 ID" value="{{ old('nonghyup_id') }}" autofocus/>
      {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password" class="form-control" placeholder="비밀번호">
      {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked>
          로그인 기억하기
          <span class="text-danger">
            (공용 컴퓨터에서는 사용하지 마세요!)
          </span>
        </label>
      </div>
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        로그인
      </button>
    </div>
  </form>
@stop
