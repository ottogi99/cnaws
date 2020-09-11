@extends('layouts.app')

@section('title', '사업현황')

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
</style>
@stop

@section('content')
<div class="box col-md-12">
  <div class="box-inner" style="background-color:#ffffff;">
    <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>운영실적</span>
    </div>
    <div class="box-content">
        @include('performance_executive.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <!-- <th rowspan='3'>선택</th> -->
            <th rowspan='3'>번호</th>
            <th colspan='2'>지역정보</th>
            <th colspan='4'>일반지원단</th>
            <th colspan='4'>전문지원단</th>
            <th rowspan='3'>교육횟수</th>
            <th rowspan='3'>홍보횟수<br>(언론,소식지 등)</th>
          </tr>
            <th rowspan='2'>시군명</th>
            <th rowspan='2'>대상농협</th>
            <th colspan='2'>모집실적</th>
            <th colspan='2'>지원실적</th>
            <th colspan='2'>모집실적</th>
            <th colspan='2'>지원실적</th>
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
        </thead>
        <tbody>
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
        </tbody>
      </table>
      {{ $rows->withQueryString()->links() }}
      @if($rows->total() > 0)
        <a href="{{ route('performance_executive.export',
                ['year'=>request()->input('year'), 'nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
            class="btn btn-sm btn-primary">엑셀다운로드</a>
      @endif
    </div>
  </div>
</div>
@stop
