@extends('layouts.app')

@section('title', '입력관리')

@section('style')
<style>
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
</style>
@stop

@section('content')
<div class="box col-md-6 task6_in" id="task6_in">
  <div class="box-inner" style="background-color:#ffffff;">
    <!-- <div class="box-header well" data-original-title=""
      style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>6.</span><span>  테스트 제목입니다.</span>
    </div> -->
    <div class="box-content" style="padding-bottom:50px;">
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">데이터 입력 상태</span>
        <span class="form-control" style="width:550px; border:;">{{ ($schedule->is_allow) ? '허용' : '중단' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">입력기간 설정 상태</span>
        <span class="form-control" style="width:550px; border:;">{{ ($schedule->is_period) ? '설정' : '미설정' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">입력 시작일자</span>
        <span class="form-control" style="width:550px; border:;">{{ ($schedule->input_start_date) ? $schedule->input_start_date->format('Y-m-d') : '' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">입력 종료일자</span>
        <span class="form-control" style="width:550px; border:;">{{ ($schedule->input_end_date) ? $schedule->input_end_date->format('Y-m-d') : '' }}</span>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('edit-schedules', null)
        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
      </div>
    </div>
  </div>
</div>
@stop
