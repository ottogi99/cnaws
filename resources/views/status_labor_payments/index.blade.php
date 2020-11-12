@extends('layouts.app')

@section('title', '지원현황')

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
      <span>센터운영비(인건비)</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
        @include('status_labor_payments.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <th width="3%"></th>
            <th width="3%"></th>
            <th colspan='2'>지역정보</th>
            <th colspan='5'>전담인력</th>
            <th>근무실적</th>
            <th colspan='5'>지급액(원)</th>
            <th width="6%"></th>
            <th width="8%"></th>
          </tr>
          <tr>
            <th><input type="checkbox" id="check_all"></th>
            <th>번호</th>
            <th>시군명</th>
            <th>대상농협</th>
            <th>지출일자</th>
            <th>성명</th>
            <th>생년월일</th>
            <!-- <th>연락처</th> -->
            <th>은행명</th>
            <th>계좌번호</th>
            <th>지출내역</th>
            <th>합계</th>
            <th>도비</th>
            <th>시군비</th>
            <th>중앙회</th>
            <th>지역농협</th>
            <th>등록일자</th>
            <th>기능</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
          <tr onclick="location.href='{{ route('status_labor_payments.show', $row->id) }}'">
            <td><input type="checkbox" class="check" data-id="{{ $row->id }}"></td>
            <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
            <td>{{ $row->sigun->name }}</td>
            <td>{{ $row->nonghyup->name }}</td>
            <td>{{ $row->payment_date->format('Y-m-d') }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->birth }}</td>
            <!-- <td>{{ $row->contract }}</td> -->
            <td>{{ $row->bank_name }}</td>
            <td>{{ $row->bank_account }}</td>
            <td>{{ $row->detail }}</td>
            <td>{{ number_format($row->payment_sum) }}</td>
            <td>{{ number_format($row->payment_do) }}</td>
            <td>{{ number_format($row->payment_sigun) }}</td>
            <td>{{ number_format($row->payment_center) }}</td>
            <td>{{ number_format($row->payment_unit) }}</td>
            <td>{{ $row->created_at->format('Y-m-d') }}</td>
            <td>
              <!-- <button class="btn btn-xs" onclick="location.href='{{ route('status_labor_payments.show', $row->id) }}'">보기</button> -->
              @if (auth()->user()->is_input_allowed)
              <button class="btn btn-xs btn-primary" onclick="location.href='{{ route('status_labor_payments.edit', $row->id) }}'">수정</button>
              <button class="btn btn-xs btn-danger button__delete" data-id="{{ $row->id }}">삭제</button>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="16">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <hr/>

      <div style="float:left;">
        <button style="margin: 5px;" class="btn btn-danger btn-sm delete-all" data-url="">일괄삭제</button>
      </div>

      <div style="float:right;">
      @if (auth()->user()->is_input_allowed)
        <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('status_labor_payments.create') }}'">등록</button>
      @endif
      @if($rows->total() > 0)
        <a href="{{ route('status_labor_payments.export',
              ['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'nonghyup'=>request()->input('nonghyup_id'), 'q'=>request()->input('q')]) }}"
      	   class="btn btn-sm btn-primary">엑셀다운로드</a>
        <!-- <button type="button" class="btn btn-sm btn-success btn-open-form">엑셀 업로드</button> -->
        @endif
        <button type="button" class="btn btn-sm btn-down-example">샘플 다운로드</button>										<!-- {!! $errors->first('excel', '<span class="form-error">:message</span>') !!} -->
      </div>

      @if (auth()->user()->is_input_allowed)
      <div style="text-align:right; margin-top:45px;">
        <div class="bg-light" style="padding-top:10px;">
          <form action="{{ route('status_labor_payments.import') }}" method="POST" enctype="multipart/form-data" class="form__upload">
            @csrf
            <div class="form-group {{ $errors->has('excel') ? 'has-error' : '' }}">
              <input type="file" name="excel" id="excel" class="form-control" style="width:20%; display:inline-block;">
              <button type="submit" class="btn btn-sm btn-success" style="margin-bottom:9px;">엑셀 업로드</button>										<!-- {!! $errors->first('excel', '<span class="form-error">:message</span>') !!} -->
            </div>
            {!! $errors->first('excel', '<span class="form-error">:message</span>') !!}
          </form>
        </div>
      </div>
      @endif

      <div class="bot_pagination">
        {{ $rows->withQueryString()->links() }}
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
    var rowId = $(this).data('id');

    if (confirm('항목을 삭제합니다.')) {
      $.ajax({
        type: 'DELETE',
        url: '/status_labor_payments/' + rowId
      }).then(function() {
        window.location.href = '/status_labor_payments';
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
                url: "{{ route('status_labor_payments.multiple-delete') }}",
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
  });

  // $('.btn-open-form').on('click', function (e) {
  //   $('.form__upload').toggle();
  // })

  $('.btn-down-example').on('click', function () {
    window.location.href = '/status_labor_payments/example';
  })

  $(document).ready(function() {
    $('select#sigun_code').change(get_nonghyups);
  });
</script>
@stop
