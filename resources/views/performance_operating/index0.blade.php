@extends('layouts.app')

@section('content')
  <div class="btn_group sort__article">
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
        @include('performance_operating.partial.search')
      </asdie>
    </div>
  </div>

  <table class="table">
    <tr>
      <th>번호</th>
      <th>시군명</th>
      <th>대상농협</th>
      <th>농가모집(명)</th>
      <th>지원단모집(명)</th>
      <th>지원농가(호)</th>
      <th>면적(㏊)</th>
      <th>농가모집(명)</th>
      <th>지원단모집(명)</th>
      <th>지원농가(호)</th>
      <th>지원인력(명)</th>
    </tr>
    @forelse($rows as $row)
    <tr>
      <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
      <td>{{ $row->sigun_name }}</td>
      <td>{{ $row->nonghyup_name }}</td>
      <td>{{ $row->small_farmer_number }}</td>
      <td>{{ $row->machine_supporter_number }}</td>
      <td>{{ $row->machine_supporter_performance_days }}</td>
      <td>{{ $row->machine_supporter_working_area }}</td>
      <td>{{ $row->large_farmer_number }}</td>
      <td>{{ $row->manpower_supporter_number }}</td>
      <td>{{ $row->manpower_supporter_performance_days }}</td>
      <td>{{ $row->manpower_supporter_working_days }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="13">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $rows->withQueryString()->links() }}
  <a href="{{ route('performance_operating.export',
          ['year'=>request()->input('year'), 'nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
          class="btn btn-sm btn-primary">엑셀다운로드</a>
@stop
