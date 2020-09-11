@extends('layouts.app')

@section('content')
  <div class="btn_group sort__article">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-sort"></i>목록 정렬<span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      @foreach(config('project.sorting') as $column => $text)
        <li {!! request()->input('sort') == $column ? 'class="active"' : '' !!}>
          {!! link_for_sort($column, $text) !!}
        </li>
      @endforeach
    </ul>
  </div>

  <div class="row container__article">
    <div class="sidebar__article">
      <aside>
        @include('status_manpower_supporters.partial.search')
      </asdie>
    </div>
  </div>

  <table class="table">
    <tr>
      <th>번호</th>
      <th>시군명</th>
      <th>대상농협</th>
      <th>성명</th>
      <th>주소</th>
      <th>성별</th>
      <th>작업자명</th>
      <th>작업시작일</th>
      <th>작업종료일</th>
      <th>작업일수</th>
      <th>작업내용</th>
      <th>제공자</th>
      <th>교통비</th>
      <th>간식비</th>
      <th>마스크구입비</th>
      <th>합계</th>
      <th>도비</th>
      <th>시군비</th>
      <th>중앙회</th>
      <th>지역농협</th>
      <th>비고</th>
      <th>등록일자</th>
      <th>기능</th>
    </tr>
    @forelse($rows as $row)
    <tr>
      <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
      <td>{{ $row->sigun->name }}</td>
      <td>{{ $row->nonghyup->name }}</td>
      <td>{{ $row->farmer->name }}</td>
      <td>{{ $row->farmer->address }}</td>
      <td>{{ ($row->farmer->sex == 'M') ? '남' : '여' }}</td>
      <td>{{ $row->supporter->name }}</td>
      <td>{{ $row->job_start_date->format('Y-m-d') }}</td>
      <td>{{ $row->job_end_date->format('Y-m-d') }}</td>
      <td>{{ $row->working_days }}</td>
      <td>{{ $row->work_detail }}</td>
      <td>{{ ($row->recipient == 'S') ? '지원단' : '농가'}}</td>
      <td>{{ $row->payment_item1 }}</td>
      <td>{{ $row->payment_item2 }}</td>
      <td>{{ $row->payment_item3 }}</td>
      <td>{{ $row->payment_sum }}</td>
      <td>{{ $row->payment_do }}</td>
      <td>{{ $row->payment_sigun }}</td>
      <td>{{ $row->payment_center }}</td>
      <td>{{ $row->payment_unit }}</td>
      <td>{{ $row->remark }}</td>
      <td>{{ $row->created_at->format('Y-m-d') }}</td>
      <td>
        <a href="{{ route('status_manpower_supporters.show',  $row->id) }}" class="btn btn-sm btn-primary">보기</a>
        <a href="{{ route('status_manpower_supporters.edit',  $row->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $row->id }}">삭제</button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="23">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $rows->withQueryString()->links() }}
  @if($schedule->is_allow)
  <a href="{{ route('status_manpower_supporters.create') }}" class="btn btn-sm btn-primary">추가</a>
  @endif
  <a href="{{ route('status_manpower_supporters.export',
        ['year'=>request()->input('year'), 'nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
	class="btn btn-sm btn-primary">엑셀다운로드</a>
  @if($schedule->is_allow)
  <div class="container">
    <div class="bg-light">
      <form action="{{ route('status_manpower_supporters.import') }}" method="POST" enctype="multipart/form-data" class="form__article">
        @csrf
        <div class="form-group {{ $errors->has('excel') ? 'has-error' : '' }}">
          <label for="files">파일</label>
          <input type="file" name="excel" id="excel" class="form-control"/>
          <button type="submit" class="btn btn-success">엑셀 업로드</button>
          {!! $errors->first('excel', '<span class="form-error">:message</span>') !!}
        </div>
      </form>
    </div>
  </div>
  @endif

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
          url: '/status_manpower_supporters/' + rowId
        }).then(function() {
          window.location.href = '/status_manpower_supporters';
        });
      }
    });
  </script>
@stop
