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
        @include('performance_executive.partial.search')
      </asdie>
    </div>
  </div>

  <table class="table">
    <tr>
      <th>번호</th>
      <th>시군명</th>
      <th>대상농협</th>
      <th>예산액-합계(100%)</th>
      <th>예산액-도비(21%)</th>
      <th>예산액-시군비(49%)</th>
      <th>예산액-중앙회(20%)</th>
      <th>집행액-지역농협(10%)</th>
      <th>집행액-합계(100%)</th>
      <th>집행액-도비(21%)</th>
      <th>집행액-시군비(49%)</th>
      <th>집행액-중앙회(20%)</th>
      <th>집행액-지역농협(10%)</th>
      <th>잔액-합계(100%)</th>
      <th>잔액-도비(21%)</th>
      <th>잔액-시군비(49%)</th>
      <th>잔액-중앙회(20%)</th>
      <th>잔액-지역농협(10%)</th>
    </tr>
    @forelse($rows as $row)
    <tr>
      <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
      <td>{{ $row->sigun_name }}</td>
      <td>{{ $row->nonghyup_name }}</td>
      <td>{{ ($row->budget_sum) ? $row->budget_sum : 0 }}</td>
      <td>{{ ($row->budget_do) ? $row->budget_do : 0 }}</td>
      <td>{{ ($row->budget_sigun) ? $row->budget_sigun : 0 }}</td>
      <td>{{ ($row->budget_center) ? $row->budget_center : 0 }}</td>
      <td>{{ ($row->budget_unit) ? $row->budget_unit : 0 }}</td>
      <td>{{ ($row->payment_sum) ? $row->payment_sum : 0 }}</td>
      <td>{{ ($row->payment_do) ? $row->payment_do : 0 }}</td>
      <td>{{ ($row->payment_sigun) ? $row->payment_sigun : 0 }}</td>
      <td>{{ ($row->payment_center) ? $row->payment_center : 0 }}</td>
      <td>{{ ($row->payment_unit) ? $row->payment_unit : 0 }}</td>
      <td>{{ ($row->balance_sum) ? $row->balance_sum : 0 }}</td>
      <td>{{ ($row->balance_do) ? $row->balance_do : 0 }}</td>
      <td>{{ ($row->balance_sigun) ? $row->balance_sigun : 0 }}</td>
      <td>{{ ($row->balance_center) ? $row->balance_center : 0 }}</td>
      <td>{{ ($row->balance_unit) ? $row->balance_unit : 0 }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="18">항목이 존재하지 않습니다.</td>
    </tr>
    @endforelse
  </table>
  {{ $rows->withQueryString()->links() }}
  <a href="{{ route('performance_executive.export',
          ['year'=>request()->input('year'), 'nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
          class="btn btn-sm btn-primary">엑셀다운로드</a>
@stop
