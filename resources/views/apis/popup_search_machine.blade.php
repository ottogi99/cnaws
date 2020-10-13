@extends('layouts.roadaddr')

@section('title', '검색')

@section('style')
<style>
	a {
		color: #000000 !important;
		text-decoration: none;
	}
</style>
@stop

@section('content')
<form name="form" id="form" method="post" onsubmit="return false">
	<input type="hidden" name="currentPage" value="1"/> <!-- 요청 변수 설정 (현재 페이지. currentPage : n > 0) -->
	<input type="hidden" name="countPerPage" value="5"/><!-- 요청 변수 설정 (페이지당 출력 개수. countPerPage 범위 : 0 < n <= 100) -->
	<input type="hidden" name="resultType" value="json"/> <!-- 요청 변수 설정 (검색결과형식 설정, json) -->
	<input type="hidden" name="nonghyup_id" value="{{ $nonghyup_id }}"/>
	<input type="text" name="keyword" value="" onkeydown="enterSearchKeyword('machine');"/><!-- 요청 변수 설정 (키워드) -->
	<input type="button" onclick="getSearchResult('machine', 1);" value="지원반 검색"/>
	<div id="list">
	</div><!-- 검색 결과 리스트 출력 영역 -->
	<div class="bot_pagination"></div>
</form>
@stop
