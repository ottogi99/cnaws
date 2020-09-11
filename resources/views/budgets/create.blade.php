@extends('layouts.app')

@section('title', '사업관리')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
</style>
@stop

@section('content')
  @php $viewName = 'budgets.create'; @endphp

  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>사업비 등록</span>
      </div>

      <form class="box-content" action="{{ route('budgets.store') }}" method="POST" style="padding-bottom:50px;">
        @csrf

        @include('budgets.partial.form', [$siguns, $nonghyups, $budget])

        <div class="input-group pull-right">
          <button type="submit" class="btn btn-primary">저장</button>
        </div>
      </form>
    </div>
  </div>
@stop
