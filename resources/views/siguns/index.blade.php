@extends('layouts.app')

@section('content')
  <table class="table">
    <tr>
      <th>id</th>
      <th>시군코드</th>
      <th>일련번호</th>
      <th>시군명</th>
      <th>갱신일자</th>
      <th>삭제일자</th>
      <th>기능</th>
    </tr>
    @forelse($siguns as $sigun)
    <tr>
      <td>{!! $sigun->id !!}</td>
      <td>{!! $sigun->code !!}</td>
      <td>{!! $sigun->sequence !!}</td>
      <td>{!! $sigun->name !!}</td>
      <td>{!! $sigun->updated_at !!}</td>
      <td>{!! $sigun->deleted_at !!}</td>
      <td>
        <a href="{{ route('siguns.edit',  $sigun->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $sigun->id }}">삭제</button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  <a href="{{ route('siguns.create') }}">추가</a>

  <script>
      // 마스터 레이아웃의 HTML 헤더 영역에 CSRF 토큰값이 저장되어 있다. 그 값을 읽어서 모든 Ajax 요청 헤더 붙인다.
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
          }
      });

      $('.button__delete').on('click', function(e) {
          var sigunId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/siguns/' + sigunId
              }).then(function() {
                  window.location.href = '/siguns';
              });
          }
      });
  </script>
@stop
