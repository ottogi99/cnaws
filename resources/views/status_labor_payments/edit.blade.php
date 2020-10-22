@extends('layouts.app')

@section('title', '지원현황')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
  input[type="radio"] {margin:0; box-shadow:none; }
</style>
@stop

@section('content')
  @php $viewName = 'status_labor_payments.edit'; @endphp
  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>센터운영비(인건비) 지급현황 수정</span>
      </div>
      <form class="box-content" id="form__edit" action="{{ route('status_labor_payments.update', $row->id) }}" method="POST" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PUT') !!}

        @include('status_labor_payments.partial.form')
        <hr/>
        <div class="pull-left">
          <a href="{{ route('status_labor_payments.index') }}" class="btn btn-sm btn-primary">목록</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">수정</button>
        </div>
      </form>
    </div>
  </div>
@stop

@section('script')
  @parent
  <script type="text/javascript">
    var get_nonghyups = function() {
      var sigun_code = $('#sigun_code').val();

      // 농협 목록 가져오기
      var url = "{{ route('api.users') }}?sigun_code=" + sigun_code;

      $.get(url, function(data) {
        $('#nonghyup_id').html('');
        $.each(data.users, function(index, item) {
          var row = "<option value='" + item.nonghyup_id + "'>" + item.name + "</option>";
          $('#nonghyup_id').append(row);
        });
      });
    }

    $(document).ready(function() {
      $('#form__edit').submit(function() {
          var payment_sum = parseInt($('#payment_sum').val());
          var payment_do = parseInt($('#payment_do').val());
          var payment_sigun = parseInt($('#payment_sigun').val());
          var payment_center = parseInt($('#payment_center').val());
          var payment_unit = parseInt($('#payment_unit').val());
          var sum = (payment_do + payment_sigun + payment_center + payment_unit);

          if (payment_sum != (payment_do + payment_sigun + payment_center + payment_unit)){
            alert('각 지급액 항목의 합과 지급액 합계가 일치하지 않습니다.( 항목의 합:' + sum + ')');
            return false;
          }
      });

      $('#sigun_code').change(get_nonghyups);
      get_nonghyups();
    });
  </script>
@stop
