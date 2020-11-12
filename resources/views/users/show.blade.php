@extends('layouts.app')

@section('title', '사용자(농협)정보')

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
        <button class="btn btn btn-sm button__activate" data-id="{{ $nonghyup->id }}" data-activated="{{ $nonghyup->activated }}">{{ ($nonghyup->activated) ? '비활성화' : '활성화' }}</button>
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
        var userId = $(this).data('id');

        if (confirm('항목을 삭제합니다.')) {
          $.ajax({
              type: 'DELETE',
              url: '/users/' + userId
          }).then(function() {
              window.location.href = '/users';
          });
        }
      });

      $('.button__activate').on('click', function(e) {
        var userId = $(this).data('id');
        var activated = $(this).data('activated');
        var str_activated = (activated == '0' ? '활성화' : '비활성화')

        if (confirm('항목을 ' + str_activated + ' 하시겠습니까?')) {
          $.ajax({
            type: 'PATCH',
            url: '/users/activate/' + userId,
            data: { activated: activated, _method: 'PATCH' },
            // success: function() {
            //   alert(str_activated + '하였습니다');
            // }
          }).then(function() {
            window.location.href = '/users';
          });
        }
      });
  </script>
@stop
