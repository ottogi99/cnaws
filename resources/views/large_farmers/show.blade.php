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
        <span class="form-control" style="width:550px; border:;">{{ $farmer->sigun->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->nonghyup->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">성명</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">연령(세)</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->age }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">성별</span>
        <span class="form-control" style="width:550px; border:;">{{ ($farmer->sex == 'M') ? '남' : '여' }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->address }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->contact }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">소유경지면적(ha)</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->acreage }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">재배면적</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->cultivar }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">은행명</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->bank_name }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">계좌번호</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->bank_account }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->remark }}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:50px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">등록일자</span>
        <span class="form-control" style="width:550px; border:;">{{ $farmer->created_at->format('Y-m-d') }}</span>
      </div>

      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('large_farmers.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('edit-large-farmer', $farmer)
        <a href="{{ route('large_farmers.edit', $farmer->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('delete-large-farmer', $farmer)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $farmer->id }}">삭제</button>
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
          var farmerId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/large_farmers/' + farmerId
              }).then(function() {
                  window.location.href = '/large_farmers';
              });
          }
      });
  </script>
@stop
