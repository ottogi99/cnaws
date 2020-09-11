@extends('layouts.login')

@section('content')
<div class="ch-container">
  <div class="row">
    <div class="row" style="padding-top:200px;">
      <img src="/img/logo.png"/ style="display:block; margin:0 auto;">
      <div class="col-md-12 center login-header">
        <span style="font-size:55px;font-family:'ngb'; color:#ffffff;">
          <span style="font-size:40px;font-family:'ng';">농작업지원단</span> 업무지원시스템
        </span>
      </div>
    </div><!--/row-->

    <div class="row">
      <div class="well col-md-3 center login-box" style="background-color:rgba(0,0,0,0); border:none;">
        <form action="{{ route('sessions.store') }}" method="POST" role="form" class="form-horizontal">
          @csrf
          <fieldset>
            <div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input type="text" name="nonghyup_id" class="form-control" placeholder="아이디" value="{{ old('nonghyup_id') }}" autofocus style="font-size:15px;" >
            </div>
            {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}

            <div class="input-group input-group-lg {{ $errors->has('password') ? 'has-error' : '' }}">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="password" name="password" class="form-control" placeholder="비밀번호" style="font-size:15px;">
            </div>
            {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
            <div class="clearfix"></div>

            <div class="clearfix"></div><br>
            <p class="center col-md-13" style="">
              <button type="submit" class="btn btn-primary" style="border:none; height:54px;">로그인</button>
            </p>
          </fieldset>
        </form>
      </div>
      <!--/span-->
    </div><!--/row-->
  </div><!--/fluid-row-->
</div><!--/.fluid-container-->
@stop
