@extends('layouts.app')

@section('title', '이력조회')

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
  thead > tr > th {vertical-align: middle !important;}
  tbody tr td {text-align:center;}
  .table>thead>tr>th {border:1px solid #dddddd; }

  .bot_pagination {text-align:center; width:100%;}
  .pagination {padding:0;}
  .pagination>li {display:inline-block; margin:-2px;}
</style>
@stop

@section('content')
<div class="box col-md-12">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>사용자 이력조회</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
      <form class="form-inline" method="get" action="{{ route('user_histories.index') }}" role="search">
        <div class="input-group" style="float:right; margin-right:52px; margin-bottom:20px;">
          <input type="text" name="q" class="form-control" placeholder="검색어를 입력하세요." style="background-color:#efefef; font-size:15px; width:230px; height:35px;">
          <button class="btn btn-primary passclick" style="position:absolute; height:35px; line-height:17px;">검색</button>
        </div>
        <select name="item" id="item" >
          <option value="" selected>전체</option>
          <option value="worker_id">작업자ID</option>
          <option value="target_id">대상자ID</option>
          <option value="contents">작업내용</option>
        </select>
      </form>

      <table class="table table-condensed">
        <thead>
          <tr>
            <th>번호</th>
            <th>작업자ID</th>
            <th>대상자ID</th>
            <th>작업내용</th>
            <th>일자</th>
          </tr>
        </thead>
        <tbody>
          @forelse($histories as $history)
          <tr>
            <td>{{ ($histories->currentPage()-1) * $histories->perPage() + $loop->iteration }}</td>
            <td>{{ $history->worker_id }}</td>
            <td>{{ $history->target_id }}</td>
            <td>{{ $history->contents }}</td>
            <td>{{ $history->created_at->format('Y-m-d') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div style="float:right;">
      @if($histories->total() > 0)
        <a href="{{ route('user_histories.export',
              ['item'=>request()->input('item'), 'q'=>request()->input('q')]) }}"
      	   class="btn btn-sm btn-primary">엑셀다운로드</a>
      @endif
      </div>

      <div class="bot_pagination">
        {{ $histories->withQueryString()->links() }}
      </div>
    </div><!--box-inner-->
  </div><!--box-->
</div><!--row-->
@stop
