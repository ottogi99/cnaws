@extends('layouts.app')

@section('title', '사용자(항목) 비밀번호 수정')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
  input[type="radio"] {margin:0; box-shadow:none; }
</style>
@stop

@section('script')
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

</script>
@stop

@section('content')
  @php $viewName = 'users.edit'; @endphp

  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>사용자(농협) 비밀번호 변경</span>
      </div>
      <form class="box-content" action="{{ route('users.changePassword', $nonghyup->id) }}" method="POST" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PATCH') !!}
        <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
          <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ $nonghyup->nonghyup_id }}" class="form-control" readonly/>
        </div>
        <div class="input-group input-group-lg {{ $errors->has('password') ? 'has-error' : '' }}" style="padding-bottom:10px;">
          <span class="input-group-addon" style="width:150px; font-size:13px;">현재 비밀번호</span>
          <input type="password" name="password" class="form-control" placeholder="기존 비밀번호를 입력하세요." style="font-size:15px; width:330px;">
          {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
        </div>
        <div class="input-group input-group-lg {{ $errors->has('newPassword') ? 'has-error' : '' }}" style="padding-bottom:10px;">
          <span class="input-group-addon" style="width:150px; font-size:13px;">새 비밀번호</span>
          <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="새 비밀번호를 입력하세요." style="font-size:15px; width:330px;">
          {!! $errors->first('newPassword', '<span class="form-error">:message</span>') !!}
        </div>
        <div class="input-group input-group-lg {{ $errors->has('passwordConfirm') ? 'has-error' : '' }}" style="padding-bottom:10px;">
          <span class="input-group-addon" style="width:150px; font-size:13px;">새 비밀번호 확인</span>
          <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" placeholder="새 비밀번호를 다시입력하세요." style="font-size:15px; width:330px;">
          {!! $errors->first('passwordConfirm', '<span class="form-error">:message</span>') !!}
        </div>

        <hr/>
        <div class="pull-left">
          <a href="{{ route('users.edit', $nonghyup->id) }}" class="btn btn-sm btn-primary">이전</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">수정</button>
        </div>
      </form>

    </div>
  </div>
@stop
