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
      <span>운영실적</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
        @include('performance_operating.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <th width="3%" rowspan='3'>번호</th>
            <th colspan='2'>지역정보</th>
            <th colspan='4'>일반지원단</th>
            <th colspan='4'>전문지원단</th>
          </tr>
          <tr>
            <th rowspan='2'>시군명</th>
            <th rowspan='2'>대상농협</th>
            <th colspan='2'>모집실적</th>
            <th colspan='2'>지원실적</th>
            <th colspan='2'>모집실적</th>
            <th colspan='2'>지원실적</th>
          </tr>
          <tr>
            <th>농가모집(명)</th>
            <th>지원단모집(명)</th>
            <th>지원농가(호)</th>
            <th>면적(㏊)</th>
            <th>농가모집(명)</th>
            <th>지원단모집(명)</th>
            <th>지원농가(호)</th>
            <th>지원인력(명)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
          <tr>
            <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
            <td>{{ $row->sigun_name }}</td>
            <td>{{ $row->nonghyup_name }}</td>
            <td>{{ number_format($row->small_farmer_number) }}</td>
            <td>{{ number_format($row->machine_supporter_number) }}</td>
            <td>{{ number_format($row->machine_supporter_performance_days) }}</td>
            <td>{{ number_format($row->machine_supporter_working_area) }}</td>
            <td>{{ number_format($row->large_farmer_number) }}</td>
            <td>{{ number_format($row->manpower_supporter_number) }}</td>
            <td>{{ number_format($row->manpower_supporter_performance_days) }}</td>
            <td>{{ number_format($row->manpower_supporter_working_days) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="13">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <hr/>
      
      <div style="float:right;">
      @if($rows->total() > 0)
        <a href="{{ route('performance_operating.export',
                ['year'=>request()->input('year'), 'nonghyup'=>request()->input('nonghyup_id'), 'sigun'=>request()->input('sigun_code'), 'q'=>request()->input('q')]) }}"
          class="btn btn-sm btn-primary">엑셀다운로드</a>
      @endif
      </div>

      <div class="bot_pagination">
        {{ $rows->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>
@stop
