@if ($viewName === 'large_farmers.create')
  @if (auth()->user()->isAdmin())
    <div class="input-group input-group-lg {{ $errors->has('sigun_code') ? 'has-error' : '' }}" style="padding-bottom:10px;">
      <span class="input-group-addon" style="width:150px; font-size:13px;">시군</span>
      <select name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, '', true, true) !!}
      </select>
      {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
      <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
      <select name="nonghyup_id" id="nonghyup_id">
        {!! options_for_nonghyup($nonghyups, '', true, true) !!}
      </select>
      {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
    </div>
  @else
    <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
      <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', auth()->user()->sigun->code) }}" class="form-control" readonly/>
    </div>
    <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
      <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', auth()->user()->nonghyup_id) }}" class="form-control" readonly/>
    </div>
  @endif
@endif

@if ($viewName === 'large_farmers.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $farmer->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $farmer->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성명(*)</span>
  <input type="text" name="name" id="name" value="{{ old('name', $farmer->name) }}" class="form-control" placeholder="성명을 입력하세요" />
  {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('birth') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">생년월일(*)</span>
  <input type="text" name="birth" id="birth" class="form-control datePicker" placeholder="생년월일을 입력하세요"
  value="{{ old('birth', ($farmer->birth) ? $farmer->birth : '') }}">
  {!! $errors->first('birth', '<span class="form-error">:message</span>') !!}
</div>

<!-- 생년월일 추가하고 연령 삭제 (2020-11-09) -->
<!-- <div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연령(세)</span>
  <input type="number" name="age" id="age" value="{{ old('age', $farmer->age) }}" class="form-control" numberOnly/>
  {!! $errors->first('age', '<span class="form-error">:message</span>') !!}
</div> -->

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성별</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">남자</span>
  <input type="radio" id="male" name="sex" value="M" class="form-control" style="width:18px;" {{ !($farmer->sex == 'F') ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">여자</span>
  <input type="radio" id="female" name="sex" value="F" class="form-control" style="width:18px;" {{ ($farmer->sex == 'F') ? 'checked' : '' }}>
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
  <input type="text" name="address" id="address" value="{{ old('address', $farmer->address) }}" class="form-control" placeholder="도로명주소를 입력하세요" />
  <input type="button" value="도로명주소 검색" onclick="openAddrPopup();">
  {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('contact') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
  <input type="number" name="contact" id="contact" value="{{ old('contact', $farmer->contact) }}" maxlength="11" class="form-control" placeholder="'-' 없이 숫자만 입력하세요" numberOnly/>
  {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('acreage') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">소유경지면적(ha)</span>
  <input type="number" step="0.1" name="acreage" id="acreage" value="{{ old('acreage', $farmer->acreage) }}" class="form-control" placeholder="소수점 1자리 단위까지 입력하세요" numberOnly/>
  {!! $errors->first('acreage', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('cultivar') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">재배품목</span>
  <input type="text" name="cultivar" id="cultivar" value="{{ old('cultivar', $farmer->cultivar) }}" class="form-control" placeholder="재배품목을 입력하세요" />
  {!! $errors->first('cultivar', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('bank_name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">은행명</span>
  <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $farmer->bank_name) }}" class="form-control" placeholder="은행명을 입력하세요" />
  {!! $errors->first('bank_name', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('bank_account') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">계좌번호</span>
  <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $farmer->bank_account) }}" class="form-control" placeholder="계좌번호를 입력하세요" />
  {!! $errors->first('bank_account', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('remark') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
  <input type="text" name="remark" id="remark" value="{{ old('remark', $farmer->remark) }}" class="form-control"/>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>

@section('script')
  @parent
  <script type="text/javascript">
    $(function() {
      $('.datePicker').datepicker({
          format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
          startDate: '-100y',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
          endDate: '+1y',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
          autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
          calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
          clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
          // datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
          datesDisabled : [],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
          // daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
          daysOfWeekDisabled : [],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
          daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
          daysOfWeekHighlighted : [], //강조 되어야 하는 요일 설정
          disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
          immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false
          multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false
          multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
          templates : {
              leftArrow: '&laquo;',
              rightArrow: '&raquo;'
          }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징
          showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
          title: "",	//캘린더 상단에 보여주는 타이틀
          todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false
          // toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
          toggleActive : false,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
          weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일
          language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
      });//datepicker end
    });//ready end
  </script>
@stop
