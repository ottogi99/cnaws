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
        @include('large_farmers.partial.search')
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
      <th>소유경지면적(ha)</th>
      <th>재배품목</th>
      <th>은행명</th>
      <th>계좌번호</th>
      <th>비고</th>
      <th>등록일자</th>
      <th>기능</th>
    </tr>
    @forelse($farmers as $farmer)
    <tr>
      <td>{{ ($farmers->currentPage()-1) * $farmers->perPage() + $loop->iteration }}</td>
      <td>{{ $farmer->sigun->name }}</td>
      <td>{{ $farmer->nonghyup->name }}</td>
      <td>{{ $farmer->name }}</td>
      <td>{{ $farmer->age }}</td>
      <td>{{ ($farmer->sex == 'M') ? '남' : '여' }}</td>
      <td>{{ $farmer->address }}</td>
      <td>{{ $farmer->contact }}</td>
      <td>{{ $farmer->acreage }}</td>
      <td>{{ $farmer->cultivar }}</td>
      <td>{{ $farmer->bank_name }}</td>
      <td>{{ $farmer->bank_account }}</td>
      <td>{{ $farmer->remark }}</td>
      <td>{{ $farmer->created_at->format('Y-m-d') }}</td>
      <td>
        @if($schedule->is_allow)
        <a href="{{ route('large_farmers.show',  $farmer->id) }}" class="btn btn-sm btn-primary">보기</a>
        <a href="{{ route('large_farmers.edit',  $farmer->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $farmer->id }}">삭제</button>
        @endif
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="15">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $farmers->withQueryString()->links() }}
  @if($schedule->is_allow)
  <a href="{{ route('large_farmers.create') }}" class="btn btn-sm btn-primary">추가</a>
  @endif
  <a href="{{ route('large_farmers.export', 
  	['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'nonghyup'=>request()->input('nonghyup_id'), 'q'=>request()->input('q')]) }}"
	class="btn btn-sm btn-primary">엑셀다운로드</a>
  @if($schedule->is_allow)
  <div class="container">
    <div class="bg-light">
      <form action="{{ route('large_farmers.import') }}" method="POST" enctype="multipart/form-data" class="form__article">
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
          var farmerId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/large_farmers/' + farmerId
              }).then(function() {
                  window.location.href = '/large_farmers';
              });
          }
      });
  </script>
@stop
