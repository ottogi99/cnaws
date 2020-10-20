@extends('layouts.home')

@section('title', '')

@section('style')
<style>
  tbody {font-size:12px;}

  .well {border-bottom:none;}
  thead {border:2px solid #dddddd;}
  thead > tr > th {text-align:center;}
  tbody tr td {text-align:center;}

  .box-header span {margin-right:-30px;}
  .box-content table tr th {font-size:12px;}
  .box-content table tr td {font-size:11px;}
</style>
@stop

@section('content')
<img src="/img/main-logo.png"/ style="width:274px; height:160px; margin-bottom:100px;">
<div class="row" >
  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" class="well top-block" href="{{ route('status_machine_supporters.index') }}">
      <!-- <i class="glyphicon glyphicon-user blue"></i> -->
      <div>농기계지원반 모집</div>
      <!-- <div>507</div> -->
      <!-- <span class="notification">6</span> -->
    </a>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" class="well top-block" href="{{ route('status_manpower_supporters.index') }}">
      <!-- <i class="glyphicon glyphicon-star green"></i> -->
      <div>인력지원반 모집</div>
      <!-- <div>228</div> -->
      <!-- <span class="notification green">4</span> -->
    </a>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" class="well top-block" href="{{ route('notice.index') }}">
      <!-- <i class="glyphicon  glyphicon glyphicon-volume-up yellow"></i> -->
      <div>공지사항</div>
      <!-- <div>52</div> -->
      <!-- <span class="notification yellow">34</span> -->
    </a>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" class="well top-block" href="{{ route('suggestion.index') }}">
      <!-- <i class="glyphicon glyphicon-envelope red"></i> -->
      <div>건의사항</div>
      <!-- <div>25</div> -->
      <!-- <span class="notification red">12</span> -->
    </a>
  </div>
</div>

<div class="row">
  <div class="box col-md-3">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; text-align:center; line-height:60px; font-size:15px;">
        <span>농기계지원반 지원현황</span>
      </div>
      <div class="box-content">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>번호</th>
              <th>시군명</th>
              <th>대상농협</th>
              <th>지원농가</th>
              <th>작업자</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows_machine as $row)
            <tr onclick="location.href='{{ route('status_machine_supporters.show', $row->id) }}'">
              <td>{{ ($rows_machine->currentPage()-1) * $rows_machine->perPage() + $loop->iteration }}</td>
              <td>{{ $row->sigun->name }}</td>
              <td>{{ $row->nonghyup->name }}</td>
              <td>{{ $row->farmer->name }}</td>
              <td>{{ $row->supporter->name }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">항목이 존재하지 않습니다.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{ $rows_machine->withQueryString()->links() }}
      </div>
    </div>
  </div>

  <div class="box col-md-3">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title=""
           style="background:none; height:70px; text-align:center; line-height:60px; font-size:15px;">
        <span>인력지원반 지원현황</span>
      </div>
      <div class="box-content">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>번호</th>
              <th>시군명</th>
              <th>대상농협</th>
              <th>지원농가</th>
              <th>작업자</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows_manpower as $row)
            <tr onclick="location.href='{{ route('status_manpower_supporters.show', $row->id) }}'">
              <td>{{ ($rows_manpower->currentPage()-1) * $rows_manpower->perPage() + $loop->iteration }}</td>
              <td>{{ $row->sigun->name }}</td>
              <td>{{ $row->nonghyup->name }}</td>
              <td>{{ $row->farmer->name }}</td>
              <td>{{ $row->supporter->name }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">항목이 존재하지 않습니다.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{ $rows_manpower->withQueryString()->links() }}
      </div>
    </div>
  </div>

  <div class="box col-md-3">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title=""
      style="background:none; height:70px; text-align:center; line-height:60px; font-size:15px;">
        <span>공지사항</span>

        <!-- <div class="box-icon">
          <a href="#" class="btn btn-minimize btn-round btn-default">
          <i class="glyphicon glyphicon-chevron-up"></i></a>
        </div> -->
      </div>
      <div class="box-content">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>작성자</th>
              <th>작성일</th>
              <th>조회수</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows_notices as $row)
            <tr onclick="location.href='{{ route('notice.show', $row->id) }}'">
              <td>{{ ($rows_notices->currentPage()-1) * $rows_notices->perPage() + $loop->iteration }}</td>
              <td>{{ $row->title }}</td>
              <td>{{ $row->user->name }}</td>
              <td>{{ $row->created_at->format('Y-m-d') }}</td>
              <td>{{ $row->hit }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">항목이 존재하지 않습니다.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{ $rows_notices->withQueryString()->links() }}
      </div>
    </div>
  </div>

  <div class="box col-md-3">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title=""
      style="background:none; height:70px; text-align:center; line-height:60px; font-size:15px;">
        <span>건의사항</span>

        <!-- <div class="box-icon">
          <a href="#" class="btn btn-minimize btn-round btn-default">
          <i class="glyphicon glyphicon-chevron-up"></i></a>
        </div> -->
      </div>
      <div class="box-content">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>작성자</th>
              <th>작성일</th>
              <th>공개여부</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows_suggestions as $row)
            <tr onclick="location.href='{{ route('suggestion.show', $row->id) }}'">
              <td>{{ ($rows_suggestions->currentPage()-1) * $rows_suggestions->perPage() + $loop->iteration }}</td>
              <td>{{ $row->title }}</td>
              <td>{{ $row->user->name }}</td>
              <td>{{ $row->created_at->format('Y-m-d') }}</td>
              <td>{{ ($row->disclose) ? '비공개' : '공개'  }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">항목이 존재하지 않습니다.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{ $rows_suggestions->withQueryString()->links() }}
        </ul>
      </div>
    </div>
  </div>
</div><!--/row-->
@stop

@section('script')
<script>
  function logout() {
    var result = confirm("정말로 로그아웃 하시겠습니까?");
    if (result) {
      location.href = "{{ route('sessions.destroy') }}";
    }
  }

  window.onload = function () {
    var view = document.getElementById('hidebtn')
    var viewcon = document.getElementById('hidecontentsbtn')
    var viewconb = document.getElementById('hidecontentsbtnb')
    var savebtn = document.getElementById('savebtn')

    view.onclick = function (){
      viewcon.style.display = 'block';
      viewconb.style.display = 'block';
    }

    savebtn.onclick = function (){
      viewcon.style.display = 'none';
      viewconb.style.display = 'none';
    }
  }
</script>
@stop
