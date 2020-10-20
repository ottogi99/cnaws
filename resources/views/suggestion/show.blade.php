@extends('layouts.app')

@section('title', '업무포탈')

@section('style')
<style>
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}

  .input-group textarea {resize:none; height: 300px !important;}

  div a {margin-top:0; margin-bottom: 0; padding-left:0px;}

  tbody {font-size:12px;}

  .well {border-bottom:none;}
	thead {border:2px solid #dddddd;}
	.container__comment .tabledet td > a {background-color:#efefef; width:40px; height:25px; display:block; color:#555555; text-align:center; line-height:25px; border-radius:5px; text-decoration:none;}
	thead > tr > th {text-align:center;}
	tbody tr td {text-align:center;}
	.tabledet tbody tr td {text-align:left;}
	.table > thead > tr > th {border:1px solid #dddddd; border-bottom:none; border-top:none;}
</style>
@stop

@section('content')
  @php $viewName = 'suggestion.show'; @endphp

<div class="box col-md-4 task6_in" id="task6_in">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>{{ $suggestion->title }}</span>
    </div>

    <suggestion data-id="{{ $suggestion->id }}"></suggestion>

    <div class="box-content" style="padding-bottom:50px;">
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">작성자</span>
        <span class="form-control">{!! $suggestion->user->name !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">내용</span>
        <span class="form-control" style="height:200px;">{!! nl2br($suggestion->content) !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">첨부파일</span>
        @forelse ($suggestion->attachments as $attachment)
        <div style="font-size:13px;">{{ $attachment->original_name }}
          <a href="{{ route('suggestion.download_file', $attachment->id) }}" class="btn btn-round btn-default">
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
        <span class="form-control">{!! $suggestion->updated_at->format('Y-m-d') !!}</span>
      </div>
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:20%; font-size:13px;">조회수</span>
        <span class="form-control">{!! $suggestion->hit !!}</span>
      </div>

      <hr/>

      <div style="float:left; margin-left:0px;">
        <style>
        .upload:hover, .upload:active {color:#ffffff;}
        </style>
        <a href="{{ route('suggestion.index') }}" class="btn btn-sm btn-primary">목록</a>
      </div>

      <div style="float:right; margin-right:0px;">
        @can('user-suggestion-update', $suggestion)
        <a href="{{ route('suggestion.edit', $suggestion->id) }}" class="btn btn-sm btn-primary">수정</a>
        @endcan
        @can('user-suggestion-delete', $suggestion)
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $suggestion->id }}">삭제</button>
        @endcan
      </div>

<!-- 댓글 영역 -->
      <div class="input-group input-group-lg {{ $errors->has('content') ? 'has-error' : '' }}" style="padding-top:20px; border-top:2px solid #efefef;">
        <span class="input-group-addon" style="width:20%; font-size:13px; height:10px;">댓글</span>
        <input type="text" class="form-control" id="comment_content" placeholder="내용을 입력하세요." style="height:40px;">
        <a id="#" href="#" class="btn btn-primary btn__add__comment" style="position:absolute; height:40px; line-height:20px;">등록</a>
        {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
      </div>

      <div class="container__comment" id="tbl_comments">
        <table class="table tabledet" style="margin-top:30px;">
          <colgroup>
            <col width="15%"/>
            <col width=""/>
            <col width="20%"/>
            <col width="10%"/>
          </colgroup>
          <tbody>
          @forelse($comments as $comment)
          <tr class="item__comment__{{ $comment->id }}" data-id="{{ $comment->id }}">
            <th style="border-right:1px solid #efefef;">{{ $comment->user->name }}</th>
            <td style="">{{ nl2br($comment->content) }}</td>
            <td class="center" style=" border-right:1px solid #efefef;">{{ $comment->created_at->format('Y-m-d') }}<br/>{{ $comment->created_at->format('h:i:s') }}</td>
            <td class="center">
              <a href="#" class="btn__delete__comment"><i class="glyphicon glyphicon-trash"></i></a>
            </td>
          </tr>
          @empty
          @endforelse
        </tbody>
        </table>
      </div>

      <table class="table tabledet" style="margin-top:10px;">
        <colgroup>
          <col width="15%"/>
          <col width=""/>
          <col width="25%"/>
        </colgroup>
        @isset($previous)
        <tr>
          <th style=" border-right:1px solid #efefef;">이전글</th>
          <td style=""><a href="{{ route('suggestion.show', $previous->id) }}" style="color: #555555;">{{ $previous->title }}</a></td>
          <td class="center">{{ $previous->updated_at->format('Y-m-d h:i:s') }}</td>
        </tr>
        @endisset
        @isset($next)
        <tr>
          <th style=" border-right:1px solid #efefef;">다음글</th>
          <td style=""><a href="{{ route('suggestion.show', $next->id) }}" style="color: #555555;">{!! $next->title !!}</a></td>
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
          // var suggestionId = $(this).data('id');
          var suggestionId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/suggestion/' + suggestionId
              }).then(function() {
                  window.location.href = '/suggestion';
              });
          }
      });

      $('.btn__add__comment').on('click', function(e) {
        var suggestionId = $('suggestion').data('id');
        var content = $('#comment_content').val();
        $('#comment_content').val('');

        if (content.length < 3) {
          alert('댓글을 3자 이상 입력해주세요');
          return false;
        }

        $.ajax({
          type: 'POST',
          url: "/comments/",
          data: {
            suggestion_id: suggestionId,
            content: content
          }
        }).then(function(data) {
          console.log(data);

          var tr = '<tr class=".item__comment__"' + data.comment.id + ' data-id="' + data.comment.id + '">' +
                    '<th style="border-right:1px solid #efefef;">'+ data.user.name + '</th>' +
                    '<td style="">' + data.comment.content + '</td>' +
                    '<td class="center" style=" border-right:1px solid #efefef;">' + data.comment.formatted_date + '</td>' +
                    '<td class="center">' +
                      '<a href="#" class="btn__delete__comment"><i class="glyphicon glyphicon-trash"></i></a>' +
                    '</td>'
                   '</tr>';

          $('#tbl_comments tr:first').before(tr);

        }).fail(function(e) {
          alert('오류가 발생하였습니다');
        });
      });

      $('.btn__delete__comment').on('click', function(e) {
        // var _self = $(this).closest('.item__comment');
        var commentId = $(this).closest('tr').data('id'), suggestionId = $('suggestion').data('id');

        console.log(commentId);

        if (confirm('댓글을 삭제합니다.')) {
          $.ajax({
            context: this,
            type: 'POST',
            url: "/comments/" + commentId,
            data: {
              _method: "DELETE"
            }
          }).then(function() {
            $(this).closest('tr').remove();
            // $('#comment_' + commentId).fadeOut(1000, function () { $(this).remove(); });
          }).fail(function(e) {
            alert('오류가 발생하였습니다');
          });
        }
      })
  </script>
@stop
