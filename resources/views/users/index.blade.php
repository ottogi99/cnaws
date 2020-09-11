@extends('layouts.app')

@section('title', '사용자(농협)정보')

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
      <span>참여농협정보</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
        @include('users.partial.search')

        <table class="table table-condensed">
          <thead>
            <tr>
              <th><input type="checkbox" id="check_all"></th>
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
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td><input type="checkbox" class="check" data-id="{{ $user->id }}"></td>
              <td>{{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}</td>
              <td>{{ $user->sigun->name }}</td>
              <td>{{ $user->nonghyup_id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->address }}</td>
              <td>{{ $user->contact }}</td>
              <td>{{ $user->representative }}</td>
              <td>{{ ($user->activated) ? '활성' : '비활성' }}
              @can('activate-user', auth()->user()->nonghyup_id)
                (
                <button class="btn btn-danger btn-xs button__activate" data-id="{{ $user->id }}" data-activated="{{ $user->activated }}">{{ ($user->activated) ? '비활성화' : '활성화' }}</button>
                )
              @endcan
              </td>
              <td>{{ ($user->isAdmin()) ? '관리자' : '사용자' }}</td>
              <td>{{ $user->updated_at->format('Y-m-d') }}</td>
              <td>
                @if($schedule->is_allow)
                  <button class="btn btn-xs" onclick="location.href='{{ route('users.show', $user->id) }}'">보기</button>
                  <button class="btn btn-xs btn-primary" onclick="location.href='{{ route('users.edit', $user->id) }}'">수정</button>
                  @can('delete-user', auth()->user()->nonghyup_id)
                  <button class="btn btn-xs btn-danger button__delete" data-id="{{ $user->id }}">삭제</button>
                  @endcan
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="12">항목이 존재하지 않습니다.</td>
            </tr>
            @if (!(request()->input('year')) || request()->input('year') == now()->year)
            <tr>
              <td colspan="12"><a href="{{ route('users.copy', now()->subYear()->format('Y')) }}" class="btn btn-sm btn-primary">전년 데이터 가져오기</td>
            </tr>
            @endif
          @endforelse
        </tbody>
      </table>

      <div style="float:left;">
        <button style="margin: 5px;" class="btn btn-danger btn-sm delete-all" data-url="">일괄삭제</button>
      </div>

      <div style="float:right;">
        @if($schedule->is_allow)
          @can('create-user', auth()->user()->nonghyup_id)
            <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('users.create') }}'">등록</button>
            <button type="submit" class="btn btn-sm btn-success" onclick="openExcelPopup();">엑셀 업로드</button>
          @endcan
        @endif
        <a href="{{ route('users.export',
            ['year'=>request()->input('year'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
            class="btn btn-sm btn-primary">엑셀다운로드</a>
      </div>

      <div class="bot_pagination">
        {{ $users->withQueryString()->links() }}
      </div>
    </div><!--box-inner-->
  </div><!--box-->
</div><!--row-->
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
            url: "{{ route('users.multiple-delete') }}",
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
</script>
@stop
