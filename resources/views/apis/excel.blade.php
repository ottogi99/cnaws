@extends('layouts.roadaddr')

@section('style')
<style>
	a {
		color: #000000 !important;
		text-decoration: none;
	}
</style>
@stop

@section('content')
<div class="container">
	<div class="bg-light">
		<form action="{{ route('small_farmers.import') }}" method="POST" enctype="multipart/form-data" class="form__upload">
			@csrf
			<div class="form-group {{ $errors->has('excel') ? 'has-error' : '' }}">
				<label for="files">파일</label>
				<input type="file" name="excel" id="excel" class="form-control"/>
				<button type="submit" class="btn btn-sm btn-success pull-right">업로드</button>
				{!! $errors->first('excel', '<span class="form-error">:message</span>') !!}
			</div>
		</form>
	</div>
</div>
@stop
