@extends('layouts.app')

@section('content')
  <div class="btn-group sort__article">
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
        @include('users.partial.search')
      </asdie>
    </div>
  </div>

  <table class="table">
    <tr>
      <th>번호</th>
      <th>시군명</th>
      <th>농협ID</th>
      <th>농협명</th>
      <th>주소</th>
      <th>연락처</th>
      <th>대표자</th>
      <th>활성화</th>
      <th>권한</th>
      <th>갱신일자</th>
      <th>삭제일자</th>
      <th>기능</th>
    </tr>
    @forelse($users as $user)
    <tr>
      <td>{{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}</td>
      <td>{{ $user->sigun->name }}</td>
      <td>{{ $user->nonghyup_id }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->address }}</td>
      <td>{{ $user->contact }}</td>
      <td>{{ $user->representative }}</td>
      <td>{{ ($user->activated) ? '활성' : '비활성' }}</td>
      <td>{{ ($user->isAdmin()) ? '관리자' : '사용자' }}</td>
      <td>{{ $user->updated_at->format('Y-m-d') }}</td>
      <td>{{ ($user->deleted_at) ? $user->deleted_at->format('Y-m-d') : '' }}</td>
      <td>
        @can('activate-user', auth()->user()->nonghyup_id)
        <button class="btn btn-danger btn-sm button__activate" data-id="{{ $user->id }}" data-activated="{{ $user->activated }}">{{ ($user->activated) ? '비활성화' : '활성화' }}</button>
        @endcan
        <a href="{{ route('users.show',  $user->id) }}" class="btn btn-sm btn-primary">보기</a>
        <a href="{{ route('users.edit',  $user->id) }}" class="btn btn-sm btn-primary">수정</a>
        @can('delete-user', auth()->user()->nonghyup_id)
          <button class="btn btn-danger btn-sm button__delete" data-id="{{ $user->id }}">삭제</button>
        @endcan
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="9">항목이 존재하지 않습니다.</td>
    </tr>

    @if (!(request()->input('year')) || request()->input('year') == now()->year)
    <tr>
      <td colspan="9"><a href="{{ route('users.copy', now()->subYear()->format('Y')) }}" class="btn btn-sm btn-primary">전년 데이터 가져오기</td>
    </tr>
    @endif
    @endforelse
  </table>
  {{ $users->withQueryString()->links() }}
  @can('create-user', auth()->user()->nonghyup_id)
  <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">추가</a>
  @endcan
  <a href="{{ route('users.export', ['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}" class="btn btn-sm btn-primary">엑셀다운로드</a>
  <a href="{{ route('users.import', 'uploaded_user.xlsx') }}" class="btn btn-sm btn-primary">엑셀업로드</a>

  <script>
      // 마스터 레이아웃의 HTML 헤더 영역에 CSRF 토큰값이 저장되어 있다. 그 값을 읽어서 모든 Ajax 요청 헤더 붙인다.
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
          }
      });

      $('.button__delete').on('click', function(e) {
          var userId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/users/' + userId
              }).then(function() {
                  window.location.href = '/users';
              });
          }
      });

      $('.button__activate').on('click', function(e) {
          var userId = $(this).data('id');
          var activated = $(this).data('activated');
          var str_activated = (activated == '0' ? '활성화' : '비활성화')

          if (confirm('항목을 ' + str_activated + ' 하시겠습니까?')) {
              $.ajax({
                  type: 'PATCH',
                  url: '/users/activate/' + userId,
                  data: { activated: activated, _method: 'PATCH' },
                  // success: function() {
                  //   alert(str_activated + '하였습니다');
                  // }
              }).then(function() {
                  window.location.href = '/users';
              });
          }
      });
  </script>
@stop
