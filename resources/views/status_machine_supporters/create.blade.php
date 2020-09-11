@extends('layouts.app')

@section('title', '지원현황')

@section('style')
  @parent
  <style>
    select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
    .input-group-addon {background:none; border:none;}
    .input-group > .form-control {width:330px; font-size:15px;}
    input[type="radio"] {margin:0; box-shadow:none; }
  </style>
@stop

@section('content')
  @php $viewName = 'status_machine_supporters.create'; @endphp
  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>농기계지원반 지원현황 등록</span>
      </div>

      <form class="box-content" action="{{ route('status_machine_supporters.store') }}" method="POST" style="padding-bottom:50px;">
        @csrf
        @include('status_machine_supporters.partial.form')
        <hr/>
        <div class="pull-left">
          <a href="{{ route('status_machine_supporters.index') }}" class="btn btn-sm btn-primary">목록</a>
        </div>
        <div class="pull-right">
          <button type="submit" class="btn btn-sm btn-primary">저장</button>
        </div>
      </form>
    </div>
  </div>
@stop

@section('script')
  @parent
  <script>
    var arr_address = {};

    var get_farmer_data = function() {
      var nonghyup_id = $('#nonghyup_id').val();

      // 소규모.영세소농 데이터 가져오기
      var url = "{{ route('small_farmers.list') }}?nonghyup_id=" + nonghyup_id;

      $.get(url, function(data) {
        $('#farmer_id').html('');
        arr_address = [];
        $.each(data, function(index, item) {
          var row = "<option value='" + item.id + "'>" + item.name + "</option>";
          $('#farmer_id').append(row);
          arr_address[item.id] = item.address;
        });

        get_farmer_address();
        get_supporter_data();
      });
    }

    var get_farmer_address = function () {
      var farmer_id = $('#farmer_id').val();
      $('input#address').val(arr_address[farmer_id]);
    }

    // 소규모.영세소농 데이터 가져오기
    var get_farmer_address_url = function () {
      var nonghyup_id = $('#nonghyup_id').val();
      var farmer_id = $('#farmer_id').val();
      var url = "{{ route('small_farmers.list') }}?nonghyup_id=" + nonghyup_id;

      $.get(url, function(data) {
        $.each(data, function(index, item) {
          if (item.id == farmer_id)
            $('input#address').val(arr_address[farmer_id]);
        });
      });
    }

    // 농기계작업반 데이터 가져오기
    var get_supporter_data = function () {
      var nonghyup_id = $('#nonghyup_id').val();
      var url = "{{ route('machine_supporters.list') }}?nonghyup_id=" + nonghyup_id;

      $.get(url, function(data) {
        $('select#supporter_id').html('');
        $.each(data, function(index, item) {
          var row = "<option value='" + item.id + "'>" + item.name + "</option>";
          $('select#supporter_id').append(row);
        });
      });
    }

    $(document).ready(function() {
      $('#nonghyup_id').change(get_farmer_data);
      get_farmer_data();
      $('#farmer_id').change(get_farmer_address_url);
    });
  </script>
@stop
