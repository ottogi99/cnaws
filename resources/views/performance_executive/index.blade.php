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
      <span>집행실적</span>
    </div>
    <div class="box-content" style="padding-bottom:50px;">
        @include('performance_executive.partial.search')
      <table class="table table-condensed">
        <thead>
          <tr>
            <th width="3%" rowspan='2'>번호</th>
            <th colspan='2'>지역정보</th>
            <th colspan='5'>예산액</th>
            <th colspan='5'>집행액</th>
            <th colspan='5'>잔액</th>
            <th rowspan='2'>집행율(%)</th>
          </tr>
          <tr>
            <th width="4%">시군명</th>
            <th width="5%">대상농협</th>
            <th>합계(100%)</th>
            <th>도비(21%)</th>
            <th>시군비(49%)</th>
            <th>중앙회(20%)</th>
            <th>지역농협(10%)</th>
            <th>합계(100%)</th>
            <th>도비(21%)</th>
            <th>시군비(49%)</th>
            <th>중앙회(20%)</th>
            <th>지역농협(10%)</th>
            <th>합계(100%)</th>
            <th>도비(21%)</th>
            <th>시군비(49%)</th>
            <th>중앙회(20%)</th>
            <th>지역농협(10%)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
          <tr>
            <td>{{ ($rows->currentPage()-1) * $rows->perPage() + $loop->iteration }}</td>
            <td>{{ $row->sigun_name }}</td>
            <td>{{ $row->nonghyup_name }}</td>
            <td>{{ ($row->budget_sum) ? number_format($row->budget_sum) : 0 }}</td>
            <td>{{ ($row->budget_do) ? number_format($row->budget_do) : 0 }}</td>
            <td>{{ ($row->budget_sigun) ? number_format($row->budget_sigun) : 0 }}</td>
            <td>{{ ($row->budget_center) ? number_format($row->budget_center) : 0 }}</td>
            <td>{{ ($row->budget_unit) ? number_format($row->budget_unit) : 0 }}</td>
            <td>{{ ($row->payment_sum) ? number_format($row->payment_sum) : 0 }}</td>
            <td>{{ ($row->payment_do) ? number_format($row->payment_do) : 0 }}</td>
            <td>{{ ($row->payment_sigun) ? number_format($row->payment_sigun) : 0 }}</td>
            <td>{{ ($row->payment_center) ? number_format($row->payment_center) : 0 }}</td>
            <td>{{ ($row->payment_unit) ? number_format($row->payment_unit) : 0 }}</td>
            <td>{{ ($row->balance_sum) ? number_format($row->balance_sum) : 0 }}</td>
            <td>{{ ($row->balance_do) ? number_format($row->balance_do) : 0 }}</td>
            <td>{{ ($row->balance_sigun) ? number_format($row->balance_sigun) : 0 }}</td>
            <td>{{ ($row->balance_center) ? number_format($row->balance_center) : 0 }}</td>
            <td>{{ ($row->balance_unit) ? number_format($row->balance_unit) : 0 }}</td>
            <td>{{ ($row->execution_rate) ? number_format($row->execution_rate, 1) : 0 }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="19">항목이 존재하지 않습니다.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <hr/>

      <div style="float:right;">
      @if($rows->total() > 0)
        <a href="{{ route('performance_executive.export',
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

@section('script')
  @parent
  <script type="text/javascript">
    var get_nonghyups = function() {
      var sigun_code = $('.form-inline > select#sigun_code').val();
      if (!sigun_code) {
        $('.form-inline > select#nonghyup_id').prop('disabled', true);
      } else {
        $('.form-inline > select#nonghyup_id').prop('disabled', false);
      }

      var url = "{{ route('users.list') }}?sigun_code=" + sigun_code;

      $.get(url, function(data) {
        $('.form-inline > select#nonghyup_id').html('');
        $('.form-inline > select#nonghyup_id').append("<option value=''>전체</option>");
        $.each(data, function(index, item) {
          var row = "<option value='" + item.nonghyup_id + "'>" + item.name + "</option>";
          $('.form-inline > select#nonghyup_id').append(row);
        });
      });
    }

    $(document).ready(function() {
      $('#sigun_code').change(get_nonghyups);
      get_nonghyups();
    });
  </script>
@stop
