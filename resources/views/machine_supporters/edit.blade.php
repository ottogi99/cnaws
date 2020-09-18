@extends('layouts.app')

@section('title', '모집현황')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
  input[type="radio"] {margin:0; box-shadow:none; }
</style>
@stop

@section('content')
  @php $viewName = 'machine_supporters.edit'; @endphp
  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>농기계지원반 수정</span>
      </div>
      <form class="box-content" action="{{ route('machine_supporters.update', $supporter->id) }}" method="POST" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PUT') !!}

        @include('machine_supporters.partial.form')
        <hr/>
        <div class="pull-left">
          <a href="{{ route('machine_supporters.index') }}" class="btn btn-sm btn-primary">목록</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">수정</button>
        </div>
      </form>
    </div>
  </div>
@stop
