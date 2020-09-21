@if ($viewName === 'status_operating_costs.create')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', auth()->user()->sigun->code) }}" class="form-control" readonly/>
  </div>

    @if (auth()->user()->isAdmin())
    <div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
      <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
      <select name="nonghyup_id" id="nonghyup_id">
        {!! options_for_nonghyup($nonghyups, '', true, true) !!}
      </select>
      {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
      </div>
    @else
    <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
      <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', auth()->user()->nonghyup_id) }}" class="form-control" readonly/>
    </div>
    @endif
@endif

@if ($viewName === 'status_operating_costs.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $row->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $row->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('payment_date') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지출일자</span>
  <input type="text" name="payment_date" id="payment_date" class="form-control datePicker"
  value="{{ old('payment_date', ($row->payment_date) ? $row->payment_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
  {!! $errors->first('payment_date', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('item') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지출항목</span>
  <input type="text" name="item" id="item" value="{{ old('item', $row->item) }}" class="form-control"/>
  {!! $errors->first('item', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('target') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급대상</span>
  <select name="target" id="target">
    <option value="지원단">지원단</option>
    <option value="농가">농가</option>
  </select>
  {!! $errors->first('target', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('details') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지출내용</span>
  <input type="text" name="detail" id="detail" value="{{ old('detail', $row->detail) }}" class="form-control"/>
  {!! $errors->first('detail', '<span class="form-error">:message</span>') !!}
</div>
<div class="input-group input-group-lg {{ $errors->has('payment_sum') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(합계)</span>
  <input type="number" name="payment_sum" id="payment_sum" value="{{ old('payment_sum', $row->payment_sum) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('payment_sum', '<span class="form-error">:message</span>') !!}
</div>

@if ($viewName === 'status_labor_payments.edit')
<div class="input-group input-group-lg {{ $errors->has('payment_do') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(도비)</span>
  <input type="number" name="payment_do" id="payment_do" value="{{ old('payment_do', $row->payment_do) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('payment_do', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_sigun') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(시군비)</span>
  <input type="number" name="payment_sigun" id="payment_sigun" value="{{ old('payment_sigun', $row->payment_sigun) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('payment_sigun', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_center') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(중앙회)</span>
  <input type="number" name="payment_center" id="payment_center" value="{{ old('payment_center', $row->payment_center) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('payment_center', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_center') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(지역농협)</span>
  <input type="number" name="payment_unit" id="payment_unit" value="{{ old('payment_unit', $row->payment_unit) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('payment_unit', '<span class="form-error">:message</span>') !!}
</div>
@endif

<div class="input-group input-group-lg {{ $errors->has('remark') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
  <input type="text" name="remark" id="remark" value="{{ old('remark', $row->remark) }}" class="form-control"/>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>

@section('script')
  @parent
  <script type="text/javascript">
    $(function() {
      $('#payment_date').datepicker({
          format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
          startDate: '-1y',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
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

      $('#birth').datepicker({
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
