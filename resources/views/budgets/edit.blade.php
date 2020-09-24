@extends('layouts.app')

@section('title', '사업관리')

@section('style')
<style>
  select {width:120px; height:50px; background-color:#efefef; border-radius:5px; border:1px solid #cccccc; float:right; margin-right:10px;}
  .input-group-addon {background:none; border:none;}
  .input-group > .form-control {width:330px; font-size:15px;}
</style>
@stop

@section('content')
  @php $viewName = 'budgets.edit'; @endphp

  <div class="box col-md-4">
    <div class="box-inner" style="background-color:#ffffff;">
      <div class="box-header well" data-original-title="" style="background:none; height:70px; line-height:60px; font-size:23px;">
        <span>사업비 수정</span>
      </div>

      <form class="box-content" action="{{ route('budgets.update', $budget->id) }}" method="POST" style="padding-bottom:50px;">
        @csrf
        {!! method_field('PUT') !!}

        @include('budgets.partial.form', [$siguns, $nonghyups, $budget])

        <hr/>

        <div class="pull-left">
          <a href="{{ route('budgets.index') }}" class="btn btn-sm btn-primary">목록</a>
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
      $('#sigun_code').change(get_nonghyups);
      get_nonghyups();
    });
  </script>
@stop
