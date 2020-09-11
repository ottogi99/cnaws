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
        <span class="input-group-addon" style="width:150px; font-size:13px;">지출일자</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->payment_date->format('Y-m-d') }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">지출항목</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->item }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">지급대상</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->target }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">지급내용</span>
        <span class="form-control" style="width:550px; border:;">{{ $row->detail }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">지급액</span>
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
        <a href="{{ route('status_operating_costs.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('edit-status-operating-cost', $row)
        <a href="{{ route('status_operating_costs.edit', $row->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('delete-status-operating-cost', $row)
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
                  url: '/status_operating_cost/' + rowId
              }).then(function() {
                  window.location.href = '/status_operating_cost';
              });
          }
      });
  </script>
@stop
