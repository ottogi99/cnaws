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
            <th colspan='5'>농기계지원반</th>
            <th colspan='5'>인력지원반</th>
          </tr>
          <tr>
            <th rowspan='2'>시군명</th>
            <th rowspan='2'>대상농협</th>
            <th colspan='2'>모집실적</th>
            <th colspan='3'>지원실적</th>
            <th colspan='2'>모집실적</th>
            <th colspan='3'>지원실적</th>
          </tr>
          <tr>
            <th>농가모집(명)</th>
            <th>지원반모집(명)</th>
            <th>지원농가(호)</th>
            <th>지원일수(일)</th>
            <th>면적(㏊)</th>
            <th>농가모집(명)</th>
            <th>지원반모집(명)</th>
            <th>지원농가(호)</th>
            <th>지원일수(일)</th>
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
            <td>{{ number_format($row->machine_supporter_supported_farmers) }}</td>
            <td>{{ number_format($row->machine_supporter_performance_days) }}</td>
            <td>{{ number_format($row->machine_supporter_working_area / 10000, 1) }}</td>
            <td>{{ number_format($row->large_farmer_number) }}</td>
            <td>{{ number_format($row->manpower_supporter_number) }}</td>
            <td>{{ number_format($row->manpower_supporter_supported_farmers) }}</td>       <!-- 하루라도 지원받은 농가수 -->
            <td>{{ number_format($row->manpower_supporter_performance_days) }}</td>        <!-- 지원일수(농가당 하루씩 계산(동일일 지원단 지원횟수 상관없이)) -->
            <td>{{ number_format($row->manpower_supporter_working_days) }}</td>            <!-- working_days의 합 -->
          </tr>
          @empty
          <tr>
            <td colspan="15">항목이 존재하지 않습니다.</td>
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
