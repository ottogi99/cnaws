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
              @if (auth()->user()->isAdmin())
              <th>입력상태</th>
              <th>계정상태</th>
              <th>권한</th>
              @endif
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
              @if (auth()->user()->isAdmin())
              <td>{{ ($user->is_input_allowed) ? '가능' : '중지' }}
              <td>{{ ($user->activated) ? '활성' : '비활성' }}</td>
              <td>{{ ($user->isAdmin()) ? '관리자' : '사용자' }}</td>
              @endif
              <td>{{ $user->updated_at->format('Y-m-d') }}</td>
              <td>
                <button class="btn btn-xs" onclick="location.href='{{ route('users.show', $user->id) }}'">보기</button>
                <button class="btn btn-xs btn-primary" onclick="location.href='{{ route('users.edit', $user->id) }}'">수정</button>
                @can('delete-user', auth()->user()->nonghyup_id)
                <button class="btn btn-xs btn-danger button__delete" data-id="{{ $user->id }}">삭제</button>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              @if (auth()->user()->isAdmin())
              <td colspan="12">항목이 존재하지 않습니다.</td>
              @else
              <td colspan="9">항목이 존재하지 않습니다.</td>
              @endif
            </tr>
          @endforelse
        </tbody>
      </table>

      <hr/>

      @if (auth()->user()->isAdmin())
      <div style="float:left;">
        <button style="margin: 5px;" class="btn btn-danger btn-sm delete-all" data-url="">선택삭제</button>
        <button style="margin: 5px;" class="btn btn-wanrning btn-sm activated-all" data-url="">선택 계정상태변경</button>
        <button style="margin: 5px;" class="btn btn-wanrning btn-sm input-allowed-all" data-url="">선택 입력상태변경</button>
      </div>
      @endif

      <div style="float:right;">
        @if (auth()->user()->is_input_allowed)
          @can('create-user', auth()->user()->nonghyup_id)
            <button type="button" class="btn btn-sm btn-primary" onclick="location.href='{{ route('users.create') }}'">등록</button>
          @endcan
        @endif

        @if (auth()->user()->isAdmin() && $users->total() > 0)
        <a href="{{ route('users.export',
            ['sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
            class="btn btn-sm btn-primary">엑셀다운로드</a>
        <button type="button" class="btn btn-sm btn-down-example">샘플 다운로드</button>
        @endif
      </div>

        @if (auth()->user()->isAdmin() && $users->total() > 0)
        <div style="text-align:right; margin-top:45px;">
          <div class="bg-light" style="padding-top:10px;">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="form__upload">
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

// 선택 계정상태 변경
  $('.activated-all').on('click', function(e) {
    var selected_ids = [];
    $(".check:checked").each(function() {
        selected_ids.push($(this).attr('data-id'));
    });

    if (selected_ids.length <= 0)
    {
      alert("계정상태를 변경할 항목을 선택하세요.");
    } else {
      if (confirm("정말로 선택된 항목들의 계정상태를 변경 하시겠습니까?"))
      {
        var strIds = selected_ids.join(",");

        $.ajax({
            url: "{{ route('users.toggle-activated') }}",
            type: 'PATCH',
            data: 'ids=' + strIds,
            success: function (data) {
                if (data['status'] == true) {
                    alert(data['message']);
                    location.reload();
                } else {
                    alert("변경시 오류가 발생하였습니다.");
                }
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
      }
    }
  });

  $('.input-allowed-all').on('click', function(e) {
    var selected_ids = [];
    $(".check:checked").each(function() {
        selected_ids.push($(this).attr('data-id'));
    });

    if (selected_ids.length <= 0)
    {
      alert("입력상태를 변경할 항목을 선택해 주세요.");
    } else {
      if (confirm("정말로 선택된 항목들의 입력상태를 변경 하시겠습니까?"))
      {
        var strIds = selected_ids.join(",");

        $.ajax({
            url: "{{ route('users.toggle-allowed') }}",
            type: 'PATCH',
            data: 'ids=' + strIds,
            success: function (data) {
                if (data['status'] == true) {
                    alert(data['message']);
                    location.reload();
                } else {
                    alert("변경시 오류가 발생하였습니다.");
                }
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
      }
    }
  });

  $('.btn-down-example').on('click', function () {
    window.location.href = '/users/example';
  })
</script>
@stop
