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
      <span>농기계지원반</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
      @include('status_machine_supporters.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <th width="3%"></th>
            <th width="3%"></th>
            <th colspan='2'>지역정보</th>
            <th colspan='3'>지원사항</th>
            <th colspan='6'>지원작업</th>
            <th colspan='5'>지급액(원)</th>
            <th width="6%"></th>
            <th width="8%"></th>
          </tr>
          <tr>
            <th><input type="checkbox" id="check_all"></th>
            <th>번호</th>
            <th width="5%">시군명</th>
            <th width="5%">대상농협</th>
            <th>성명</th>
            <th>주소</th>
            <th>성별</th>
            <th width="5%">작업자명</th>
            <th width="6%">작업시작일</th>
            <th width="6%">작업종료일</th>
            <th width="3%">작업일수</th>
            <th width="3%">작업내용</th>
            <th width="5%">작업면적(m<sup>2</sup>)</th>
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
          <tr onclick="location.href='{{ route('status_machine_supporters.show', ['id'=>$row->id, 'year'=>$row->business_year) }}'">
            <td><input type="checkbox" class="check" data-id="{{ $row->id }}"></td>
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
            <td>{{ number_format($row->working_area) }}</td>
            <td>{{ number_format($row->payment_sum) }}</td>
            <td>{{ number_format($row->payment_do) }}</td>
            <td>{{ number_format($row->payment_sigun) }}</td>
            <td>{{ number_format($row->payment_center) }}</td>
            <td>{{ number_format($row->payment_unit) }}</td>
            <td>{{ $row->created_at->format('Y-m-d') }}</td>
            <td>
              <!-- <button class="btn btn-xs" onclick="location.href='{{ route('status_machine_supporters.show', $row->id) }}'">보기</button> -->
              @if (auth()->user()->is_input_allowed)
              <button class="btn btn-xs btn-primary button__edit" data-id="{{ $row->id }}">수정</button>
              <button class="btn btn-xs btn-danger button__delete" data-id="{{ $row->id }}">삭제</button>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="20">항목이 존재하지 않습니다.</td>
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
        <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('status_machine_supporters.create') }}'">등록</button>
      @endif
      @if($rows->total() > 0)
      <a href="{{ route('status_machine_supporters.export',
            ['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'nonghyup'=>request()->input('nonghyup_id'), 'q'=>request()->input('q')]) }}"
            class="btn btn-sm btn-primary">엑셀다운로드</a>
        <!-- <button type="button" class="btn btn-sm btn-success btn-open-form">엑셀 업로드</button> -->
        @endif
        <button type="button" class="btn btn-sm btn-down-example">샘플 다운로드</button>										<!-- {!! $errors->first('excel', '<span class="form-error">:message</span>') !!} -->
      </div>

      @if (auth()->user()->is_input_allowed)
      <div style="text-align:right; margin-top:45px;">
        <div class="bg-light" style="padding-top:10px;">
          <form action="{{ route('status_machine_supporters.import') }}" method="POST" enctype="multipart/form-data" class="form__upload">
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

  $('.check').click(function(e){
    e.stopPropagation();
  });

  $('.button__edit').on('click', function(e) {
    e.stopPropagation();
    var rowId = $(this).data('id');
    window.location.href = '/status_machine_supporters/' + rowId + '/edit';
  });

  $('.button__delete').on('click', function(e) {
    e.stopPropagation();
    var rowId = $(this).data('id');

    if (confirm('항목을 삭제합니다.')) {
      $.ajax({
        type: 'DELETE',
        url: '/status_machine_supporters/' + rowId
      }).then(function() {
        window.location.href = '/status_machine_supporters';
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
                url: "{{ route('status_machine_supporters.multiple-delete') }}",
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
    window.location.href = '/status_machine_supporters/example';
  })

  $(document).ready(function() {
    $('select#sigun_code').change(get_nonghyups);
  });
</script>
@stop
