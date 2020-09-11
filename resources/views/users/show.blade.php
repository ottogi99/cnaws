@extends('layouts.app')

@section('content')
<div class="box col-md-6 task6_in" id="task6_in">
  <div class="box-inner" style="background-color:#ffffff;">
    <!-- <div class="box-header well" data-original-title=""
      style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>6.</span><span>  테스트 제목입니다.</span>
    </div> -->

    <div class="box-content" style="padding-bottom:50px;">
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">시군명</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->sigun->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">농협ID</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->nonghyup_id }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">농협명</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->address }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">연락처(담당자)</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->contact }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">대표자</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->representative }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">활성화</span>
        <span class="form-control" style="width:550px; border:;">{{ ($nonghyup->activated) ? '활성' : '비활성' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">권한</span>
        <span class="form-control" style="width:550px; border:;">{{ ($nonghyup->is_admin) ? '관리자' : '사용자' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:50px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">등록일자</span>
        <span class="form-control" style="width:550px; border:;">{{ $nonghyup->created_at->format('Y-m-d') }}</span>
      </div>

      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('activate-user', auth()->user()->nonghyup_id)
        <button class="btn btn-danger btn-sm button__activate" data-id="{{ $nonghyup->id }}" data-activated="{{ $nonghyup->activated }}">{{ ($nonghyup->activated) ? '비활성화' : '활성화' }}</button>
        @endcan
        <a href="{{ route('users.edit', $nonghyup->id) }}" class="btn btn-sm btn-primary">수정</a>
        @can('delete-user', auth()->user()->nonghyup_id)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $nonghyup->id }}">삭제</button>
        @endcan
      </div>
    </div>
  </div>
</div>
@stop
