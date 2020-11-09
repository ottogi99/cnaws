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
        <span class="form-control" style="width:550px; border:;">{{ $row->sigun->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->nonghyup->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">농가명</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->farmer->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">농가주소</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->farmer->address }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">농가성별</span>
        <span class="form-control" style="width:550px; border:;">{{ ($row->farmer->sex == 'M') ? '남' : '여' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업자명</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->supporter->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업자주소</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->supporter->address }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업시작일</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->job_start_date->format('Y-m-d') }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업종료일</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->job_end_date->format('Y-m-d') }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업일수</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->working_days }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업내용</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->work_detail }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">작업면적</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->working_area }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">합계</span>
        <span class="form-control" style="width:550px; border:;">{{ number_format($row->payment_sum) }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">도비(21%)</span>
        <span class="form-control" style="width:550px; border:;">{{ number_format($row->payment_do) }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">시군비(49%)</span>
        <span class="form-control" style="width:550px; border:;">{{ number_format($row->payment_sigun) }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">중앙회(20%)</span>
        <span class="form-control" style="width:550px; border:;">{{ number_format($row->payment_center) }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">지역농협(10%)</span>
        <span class="form-control" style="width:550px; border:;">{{ number_format($row->payment_unit) }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->remark }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:50px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">등록일자</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->created_at->format('Y-m-d') }}</span>
      </div>
      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('status_machine_supporters.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('edit-status-machine-supporter', $row)
        <a href="{{ route('status_machine_supporters.edit', $row->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('delete-status-machine-supporter', $row)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $row->id }}">삭제</button>
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
          var rowId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/status_machine_supporters/' + rowId
              }).then(function() {
                  window.location.href = '/status_machine_supporters';
              });
          }
      });
  </script>
@stop
