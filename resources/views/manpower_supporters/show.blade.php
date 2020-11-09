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
        <span class="form-control" style="width:550px; border:;">{{ $supporter->sigun->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->nonghyup->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">성명</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <!-- <span class="input-group-addon" style="width:150px; font-size:13px;">연령(세)</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->age }}</span> -->
        <span class="input-group-addon" style="width:150px; font-size:13px;">생년월일(연령)</span>
        <span class="form-control" style="width:550px; border:;">
          {{ $supporter->birth }} ({{ Carbon\Carbon::parse($supporter->birth)->diffInYears(Carbon\Carbon::now()) }})
        </span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">성별</span>
        <span class="form-control" style="width:550px; border:;">{{ ($supporter->sex == 'M') ? '남' : '여' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->address }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
        <!-- <span class="form-control" style="width:550px; border:;">{{ $supporter->contact }}</span> -->
        <span class="form-control" style="width:550px; border:;">{{ $supporter->phoneNumber() }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일1</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->training_date1 }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일2</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->training_date2 }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일3</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->training_date3 }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">상해보험가입여부</span>
        <span class="form-control" style="width:550px; border:;">{{ ($supporter->has_insurance == 1) ? '가입' : '미가입' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">은행명</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->bank_name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">계좌번호</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->bank_account }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->remark }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:50px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">등록일자</span>
        <span class="form-control" style="width:550px; border:;">{{ $supporter->created_at->format('Y-m-d') }}</span>
      </div>

      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('manpower_supporters.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('delete-manpower-supporter', $supporter)
        <a href="{{ route('manpower_supporters.edit', $supporter->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('delete-manpower-supporter', $supporter)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $supporter->id }}">삭제</button>
        @endcan
      </div>
    </div>
  </div>
  </div>
@stop

@section('script')
  @parent
  <script>
      // 마스터 레이아웃의 HTML 헤더 영역에 CSRF 토큰값이 저장되어 있다. 그 값을 읽어서 모든 Ajax 요청 헤더 붙인다.
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
          }
      });

      $('.button__delete').on('click', function(e) {
          var supporterId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/manpower_supporters/' + supporterId
              }).then(function() {
                  window.location.href = '/manpower_supporters';
              });
          }
      });
  </script>
@stop
