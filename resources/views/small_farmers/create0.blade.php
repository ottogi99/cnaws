@extends('layouts.app')

@section('content')
  @php $viewName = 'small_farmers.create'; @endphp
  <div class="container">
    <h1>일손필요농가(소규모·영세농) 항목 생성</h1>
    <hr/>

    <form action="{{ route('small_farmers.store') }}" method="POST" class="form__siguns">
      @csrf

      @include('small_farmers.partial.form')

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          저장
        </button>
      </div>
    </form>
  </div>
@stop
