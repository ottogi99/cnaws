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
        @include('machine_supporters.partial.search')
      </asdie>
    </div>
  </div>

  <table class="table">
    <tr>
      <th>번호</th>
      <th>시군명</th>
      <th>대상농협</th>
      <th>성명</th>
      <th>연령(세)</th>
      <th>성별</th>
      <th>주소</th>
      <th>연락처</th>
      <th>농기계1</th>
      <th>농기계2</th>
      <th>농기계3</th>
      <th>농기계4</th>
      <th>은행명</th>
      <th>계좌번호</th>
      <th>등록일자</th>
      <th>기능</th>
    </tr>
    @forelse($supporters as $supporter)
    <tr>
      <td>{{ ($supporters->currentPage()-1) * $supporters->perPage() + $loop->iteration }}</td>
      <td>{{ $supporter->sigun->name }}</td>
      <td>{{ $supporter->nonghyup->name }}</td>
      <td>{{ $supporter->name }}</td>
      <td>{{ $supporter->age }}</td>
      <td>{{ ($supporter->sex == 'M') ? '남' : '여' }}</td>
      <td>{{ $supporter->address }}</td>
      <td>{{ $supporter->contact }}</td>
      <td>{{ $supporter->machine1 }}</td>
      <td>{{ $supporter->machine2 }}</td>
      <td>{{ $supporter->machine3 }}</td>
      <td>{{ $supporter->machine4 }}</td>
      <td>{{ $supporter->bank_name }}</td>
      <td>{{ $supporter->bank_account }}</td>
      <td>{{ $supporter->created_at->format('Y-m-d') }}</td>
      <td>
        <a href="{{ route('machine_supporters.show',  $supporter->id) }}" class="btn btn-sm btn-primary">보기</a>
        <a href="{{ route('machine_supporters.edit',  $supporter->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $supporter->id }}">삭제</button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="16">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $supporters->withQueryString()->links() }}
  <a href="{{ route('machine_supporters.create') }}">추가</a>
  <a href="{{ route('machine_supporters.export', ['nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}">엑셀다운로드</a>
  <a href="{{ route('machine_supporters.import', 'uploaded_machine_supporters.xlsx') }}">엑셀업로드</a>

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
                  url: '/machine_supporters/' + supporterId
              }).then(function() {
                  window.location.href = '/machine_supporters';
              });
          }
      });
  </script>
@stop
