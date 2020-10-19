@extends('layouts.app')

@section('title', '사용자(항목) 수정')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
  input[type="radio"] {margin:0; box-shadow:none; }
</style>
@stop

@section('content')
  @php $viewName = 'users.edit'; @endphp

  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>사용자(농협) 수정</span>
      </div>

      <form class="box-content" action="{{ route('users.update', $nonghyup->id) }}" method="POST" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PUT') !!}
        @include('users.partial.form')

        <hr/>
        <div class="pull-left">
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">목록</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">수정</button>
        </div>
      </form>
    </div>
  </div>
@stop

@section('script')
  @parent
  <script>
    $('.btn-setting').click(function (e) {
        e.preventDefault();
        $('#myModal').modal('show');
    });

    $('#savebtn').on('click', function() {
        var new_password = $('#newPassword').val().trim();
        var password_confirm = $('#passwordConfirm').val().trim();

        if (new_password != password_confirm){
          alert('비밀번호가 일치하지 않습니다. 다시 시도해주세요');
          return false;
        }

        $('.modal-body').submit();
    });

    $('.button__resetPassword').on('click', function(e) {
      var userId = $(this).data('id');

      if (confirm('정말로 비밀번호를 초기화하시겠습니까?')) {
        $.ajax({
          type: 'PATCH',
          url: '/users/' + userId + '/resetPassword',
          data: { _method: 'PATCH' },
          // success: function() {
          //   alert('비밀번호를 초기화 하였습니다');
          // }
        }).then(function() {
          alert('비밀번호를 초기화 하였습니다');
          // window.location.href = '/users';
        }).fail(function() {
          alert('오류가 발생하였습니다');
        });
      }
    });
  </script>
@stop
