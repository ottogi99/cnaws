@extends('layouts.app')

@section('title', '업무포털')

@section('style')
<style>
  tbody {font-size:12px;}

  .well {border-bottom:none;}
  thead {border:2px solid #dddddd;}
  select {width:80px; height:35px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}

  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}

  .adminsaved {display:none;}
  .adminre {display:none;}
  thead > tr > th {text-align:center;}
  tbody tr td {text-align:center;}
  .table>thead>tr>th {border:1px solid #dddddd; }

  .bot_pagination {text-align:center; width:100%;}
  .pagination {padding:0;}
  .pagination>li {display:inline-block; margin:-2px;}

  td a {color:black;}
</style>
@stop

@section('content')
<div class="box col-md-12">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>건의사항</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
      @include('suggestion.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <th width="5%">번호</th>
            <th width="40%">제목</th>
            <th width="15%">작성자</th>
            <th width="5%">조회수</th>
            <th width="10%">공개여부</th>
            <th width="10%">등록일자</th>
            <th width="15%">기능</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suggestions as $suggestion)
          <tr onclick="location.href='{{ route('suggestion.show', $suggestion->id) }}'">
            <td>{{ ($suggestions->currentPage()-1) * $suggestions->perPage() + $loop->iteration }}</td>
            <td>{{ $suggestion->title }}</td>
            <td>{{ $suggestion->user->name }}</td>
            <td>{{ $suggestion->hit }}</td>
            <td>{{ ($suggestion->disclose) ? '비공개' : '공개' }}</td>
            <td>{{ $suggestion->created_at->format('Y-m-d') }}</td>
            <td>
              @if (auth()->user()->is_input_allowed)
              <!-- <button class="btn btn-xs" onclick="location.href='{{ route('suggestion.show', $suggestion->id) }}'">보기</button> -->
              @can('suggestion-edit', $suggestion)
              <button class="btn btn-xs btn-primary" onclick="location.href='{{ route('suggestion.edit', $suggestion->id) }}'">수정</button>
              @endcan
              @can('suggestion-delete', $suggestion)
              <button class="btn btn-xs btn-danger button__delete" data-id="{{ $suggestion->id }}">삭제</button>
              @endcan
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <hr/>

      <div style="float:right;">
        <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('suggestion.create') }}'">등록</button>
      </div>

      <div class="bot_pagination">
        {{ $suggestions->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>
@stop

@section('script')
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
        url: '/suggestion/' + supporterId
      }).then(function() {
        window.location.href = '/suggestion';
      });
    }
  });
</script>
@stop
