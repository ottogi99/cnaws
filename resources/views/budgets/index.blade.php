@extends('layouts.app')

@section('title', '사업관리')

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
</style>
@stop

@section('content')
<div class="box col-md-12">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>사업비</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
      @include('budgets.partial.search')
      <table class="table table-condensed">
        <colgroup>
          <col width="3%"/>
          <col width="10%"/>
          <col width=""/>
          <col width="%"/>
          <col width=""/>
          <col width=""/>
          <col width="10%"/>
          <col width="10%"/>
        </colgroup>
        <thead>
          <tr>
            <th><input type="checkbox" id="check_all"></th>
            <th>번호</th>
            <th>년도</th>
            <th>시군명</th>
            <th>농협명</th>
            <th>사업비</th>
            <th>갱신일자</th>
            <th>기능</th>
          </tr>
        </thead>
        <tbody>
          @forelse($budgets as $budget)
          <tr>
            <td><input type="checkbox" class="check" data-id="{{ $budget->id }}"></td>
            <td>{{ ($budgets->currentPage()-1) * $budgets->perPage() + $loop->iteration }}</td>
            <td>{!! $budget->business_year !!}</td>
            <td>{!! $budget->sigun->name !!}</td>
            <td>{!! $budget->nonghyup->name !!}</td>
            <td>{!! number_format($budget->amount) !!}</td>
            <td>{!! $budget->updated_at->format('Y-m-d') !!}</td>
            <td>
              <button class="btn btn-xs" onclick="location.href='{{ route('budgets.edit', $budget->id) }}'" data-id="{{ $budget->id }}">수정</button>
              <button class="btn btn-danger btn-xs button__delete" data-id="{{ $budget->id }}">삭제</button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div style="float:left;">
        <button style="margin: 5px;" class="btn btn-danger btn-sm delete-all" data-url="">일괄삭제</button>
      </div>

      <div style="float:right;">
        @if (auth()->user()->is_input_allowed)
        <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('budgets.create') }}'">등록</button>
        @endif
        <a href="{{ route('budgets.export',
        	 ['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'nonghyup'=>request()->input('nonghyup_id'), 'q'=>request()->input('q')]) }}"
      	   class="btn btn-sm btn-primary">엑셀다운로드</a>
      </div>

      <div class="bot_pagination">
        {{ $budgets->withQueryString()->links() }}
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

  $('#check_all').on('click', function(e) {
      if($(this).is(':checked', true))
      {
          $(".check").prop('checked', true);
      } else {
          $(".check").prop('checked', false);
      }
  });

  $('.delete-all').on('click', function(e) {
      var idsArr = [];
      $(".check:checked").each(function() {
          idsArr.push($(this).attr('data-id'));
      });

      if (idsArr.length <= 0)
      {
        alert("삭제할 항목을 선택해 주세요.");
      } else {
          if (confirm("정말로 선택된 항목을 삭제하시겠습니까?"))
          {
            var strIds = idsArr.join(",");

            $.ajax({
                url: "{{ route('budgets.multiple-delete') }}",
                type: 'DELETE',
                data: 'ids=' + strIds,
                success: function (data) {
                    if (data['status'] == true) {
                        // $(".check:checked").each(function() {
                        //   $(this).parents("tr").remove();
                        // });
                        alert(data['message']);
                        location.reload();
                    } else {
                        alert("삭제시 오류가 발생하였습니다.");
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
          }
      }
  })
</script>
@stop
