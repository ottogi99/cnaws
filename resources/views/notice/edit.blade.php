@extends('layouts.app')

@section('title', '업무포털')

@section('style')
<style>
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}

  .input-group textarea {resize:none; height: 300px !important;}
</style>
@stop

@section('content')
  @php $viewName = 'notice.edit'; @endphp
  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>공지사항 수정</span>
      </div>

      <form class="box-content" action="{{ route('notice.update', $notice->id) }}" method="POST" enctype="multipart/form-data" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PUT') !!}

        @include('notice.partial.form')
        <hr/>
        <div class="pull-left">
          <a href="{{ route('notice.index') }}" class="btn btn-sm btn-primary">목록</a>
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
    $('.attachment__delete').on('click', function(e) {
        // var noticeId = $(this).data('id');
        var attachmentId = $(this).data('id');

        if (confirm('첨부파일을 삭제하시겠습니까?')) {
            $.ajax({
                type: 'DELETE',
                url: '/notice/' + attachmentId + '/delete_file'
            }).then(function() {
                // window.location.href = '/notice';
                // console.log('#attachment_' + attachmentId);
                $('#attachment_' + attachmentId).remove();
            });
        }
    });
  </script>
@stop
