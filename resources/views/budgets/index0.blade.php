@extends('layouts.app')

@section('content')
  <table class="table">
    <tr>
      <th>년도</th>
      <th>시군명</th>
      <th>농협명</th>
      <th>사업비</th>
      <th>갱신일자</th>
      <th>기능</th>
    </tr>
    @forelse($budgets as $budget)
    <tr>
      <td>{!! $budget->business_year !!}</td>
      <td>{!! $budget->sigun->name !!}</td>
      <td>{!! $budget->nonghyup->name !!}</td>
      <td>{!! $budget->amount !!}</td>
      <td>{!! $budget->updated_at->format('Y-m-d') !!}</td>
      <td>
        <a href="{{ route('budgets.edit',  $budget->id) }}" class="btn btn-sm btn-primary">수정</a>
        <button class="btn btn-danger btn-sm button__delete" data-id="{{ $budget->id }}">삭제</button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="7">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $budgets->links() }}
  <a href="{{ route('budgets.create') }}">추가</a>

  <script>
      // 마스터 레이아웃의 HTML 헤더 영역에 CSRF 토큰값이 저장되어 있다. 그 값을 읽어서 모든 Ajax 요청 헤더 붙인다.
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
          }
      });

      $('.button__delete').on('click', function(e) {
          var budgetId = $(this).data('id');

          if (confirm('항목을 삭제합니다.')) {
              $.ajax({
                  type: 'DELETE',
                  url: '/budgets/' + budgetId
              }).then(function() {
                  window.location.href = '/budgets';
              });
          }
      });
  </script>
@stop
