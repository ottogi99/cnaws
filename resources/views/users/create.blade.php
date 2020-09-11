@extends('layouts.app')

@section('title', '사용자(농협)정보')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
  input[type="radio"] {margin:0; box-shadow:none; }
</style>
@stop

@section('content')
  @php $viewName = 'users.create'; @endphp

  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>사용자(항목) 등록</span>
      </div>

      <form class="box-content" action="{{ route('users.store') }}" method="POST" style="padding-bottom:50px;">
        @csrf
        @include('users.partial.form')
        <div class="input-group pull-right">
          <button type="submit" class="btn btn-primary">저장</button>
        </div>
      </form>
    </div>
  </div>
@stop