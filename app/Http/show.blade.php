@extends('layouts.app')

@section('content')
  <p>담당 시군명: {{ $user->sigun->name }}</p>
  <p>사용자(농협)명: {{ $user->name }}</p>
  <p>사용자 ID: {{ $user->user_id }}</p>
  <p>주소: {{ $user->address }}</p>
  <p>연락처: {{ $user->contact }}</p>
  <p>대표자: {{ $user->representative }}</p>
  <p>활성화: {{ $user->activated }}</p>
  <p>관리자: {{ ($user->is_admin) ? '관리자' : '농협' }}</p>
  <p>등록일자: {{ $user->created_at }}</p>
  <p>삭제일자: {{ $user->deleted_at }}</p>
@stop
