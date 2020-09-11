@extends('layouts.app')

@section('title', '사업관리')

@section('content')
  @php $viewName = 'budgets.create'; @endphp
  <div class="container">
    <h1>사업비 항목 등록</h1>
    <hr/>

    <form action="{{ route('budgets.store') }}" method="POST" class="form__siguns">
      @csrf

      @include('budgets.partial.form', [$siguns, $nonghyups, $budget])

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          저장
        </button>
      </div>
    </form>
  </div>
@stop
