@extends('layouts.app')

@section('title', '업무포털')

@section('style')
<style>
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}

  .input-group textarea {resize:none; height: 300px !important;}

  div a {margin-top:0; margin-bottom: 0; padding-left:10px;}
</style>
@stop

@section('content')
  @php $viewName = 'articles.show'; @endphp

<div class="box col-md-4 task6_in" id="task6_in">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>{{ $notice->title }}</span>
    </div>

    <div class="box-content" style="padding-bottom:50px;">
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">작성자</span>
        <span class="form-control">{!! $notice->user->name !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">내용</span>
        <span class="form-control" style="height:auto; min-height:200px;">{!! nl2br($notice->content) !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">첨부파일</span>
        @forelse ($notice->attachments as $attachment)
        <div style="font-size:13px;">{{ $attachment->original_name }}
          <a href="{{ route('notice.download_file', $attachment->id) }}" class="btn btn-round btn-default">
            <i class="glyphicon glyphicon glyphicon-download-alt"></i>
          </a>
        </div>
        @empty
        <div style="font-size:13px;">
          </a>
        </div>
        @endforelse
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">수정일자</span>
        <span class="form-control">{!! $notice->updated_at->format('Y-m-d') !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">조회수</span>
        <span class="form-control">{!! $notice->hit !!}</span>
      </div>

      <hr/>

      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('notice.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:30px;">
        @can('notice-update', $notice)
        <a href="{{ route('notice.edit', $notice->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('notice-delete', $notice)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $notice->id }}">삭제</button>
        @endcan
      </div>

      <table class="table tabledet" style="margin-top:100px;">
        <colgroup>
          <col width="15%"/>
          <col width=""/>
          <col width="25%"/>
        </colgroup>
        @isset($previous)
        <tr>
          <th style=" border-right:1px solid #efefef;">이전글</th>
          <td style=""><a href="{{ route('notice.show', $previous->id) }}" style="color: #555555;">{{ $previous->title }}</a></td>
          <td class="center">{{ $previous->updated_at->format('Y-m-d h:i:s') }}</td>
        </tr>
        @endisset
        @isset($next)
        <tr>
          <th style=" border-right:1px solid #efefef;">다음글</th>
          <td style=""><a href="{{ route('notice.show', $next->id) }}" style="color: #555555;">{!! $next->title !!}</a></td>
          <td class="center">{{ $next->updated_at->format('Y-m-d h:i:s') }}</td>
          </tr>
        @endisset
      </table>
    </div>
  </div>
</div>
@stop

@section('script')
  @parent
  <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
          }
      });

      $('.button__delete').on('click', function(e) {
          // var noticeId = $(this).data('id');
          var noticeId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/notice/' + noticeId
              }).then(function() {
                  window.location.href = '/notice';
              });
          }
      });
  </script>
@stop
