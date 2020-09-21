@extends('layouts.app')

@section('content')
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
      <td>{{ ($user->activated) ? '관리자' : '일반(농협)' }}</td>
      <td>{{ $user->updated_at }}</td>
      <td>
        <a href="{{ route('users.show',  $user->id) }}" class="btn btn-sm btn-primary">보기</a>
        <a href="{{ route('users.edit',  $user->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $user->id }}">삭제</button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="9">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $users->links() }}
  <a href="{{ route('users.create') }}">추가</a>

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
  </script>
@stop
