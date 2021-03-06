@extends('layouts.app')

@section('title', '업무포털')

@section('style')
<style>
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}

  .input-group textarea {resize:none; height: 300px !important;}
</style>
@stop

@section('content')
  @php $viewName = 'user_manual.create'; @endphp
  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>사용자매뉴얼 등록</span>
      </div>

      <form class="box-content" action="{{ route('user_manual.store') }}" method="POST" enctype="multipart/form-data" style="padding-bottom:50px;">
        @csrf
        @include('user_manual.partial.form')
        <hr/>
        <div class="pull-left">
          <a href="{{ route('user_manual.index') }}" class="btn btn-sm btn-primary">목록</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">저장</button>
        </div>
      </form>
    </div>
  </div>
@stop

@section('script')
  @parent

@stop
