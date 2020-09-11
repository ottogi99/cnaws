@extends('layouts.app')

@section('content')
  <p>시군명: {{ $supporter->sigun->name }}</p>
  <p>대상농협: {{ $supporter->nonghyup->name }}</p>
  <p>성명: {{ $supporter->name }}</p>
  <p>연령(세): {{ $supporter->age }}</p>
  <p>성별: {{ $supporter->sex }}</p>
  <p>주소: {{ $supporter->address }}</p>
  <p>연락처: {{ $supporter->contact }}</p>
  <p>농기계1: {{ $supporter->machine1 }}</p>
  <p>농기계2: {{ $supporter->machine2 }}</p>
  <p>농기계3: {{ $supporter->machine3 }}</p>
  <p>농기계4: {{ $supporter->machine4 }}</p>
  <p>은행명: {{ $supporter->bank_name }}</p>
  <p>계좌번호: {{ $supporter->bank_account }}</p>
  <p>비고: {{ $supporter->remark }}</p>
  <p>등록일자: {{ $supporter->created_at->format('Y-m-d') }}</p>
@stop
