@if ($viewName === 'status_manpower_supporters.create')
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

@if ($viewName === 'status_manpower_supporters.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $row->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $row->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('farmer_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">농가명(*)</span>
  <input type="text" name="farmer_name" id="farmer_name" value="{{ old('farmer_name', ($row->farmer) ? $row->farmer->name : '') }}"
        class="form-control" placeholder="농가를 검색하여 선택하세요" readonly/>
  <div>{!! $errors->first('farmer_id', '<span class="form-error">:message</span>') !!}</div>
  <input type="button" value="농가 검색" onclick="openSearchPopup('large');">
</div>

<div class="input-group input-group-lg {{ $errors->has('farmer_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <input type="hidden" name="farmer_id" id="farmer_id" value="{{ old('farmer_id', ($row->farmer) ? $row->farmer->id : '') }}" class="form-control"  />
</div>

<div class="input-group input-group-lg {{ $errors->has('address') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">농가주소</span>
    <input type="text" name="address" id="address" value="{{ old('address', ($row->farmer) ? $row->farmer->address : '') }}" class="form-control" readonly/>
</div>

<div class="input-group input-group-lg {{ $errors->has('supporter_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">작업자명(*)</span>
  <input type="text" name="supporter_name" id="supporter_name" value="{{ old('supporter_name', ($row->supporter) ? $row->supporter->name : '') }}"
        class="form-control" placeholder="작업자를 검색하여 선택하세요" readonly/>
  <div>{!! $errors->first('supporter_id', '<span class="form-error">:message</span>') !!}</div>
  <input type="button" value="지원반 검색" onclick="openSearchPopup('manpower');">
</div>

<div class="input-group input-group-lg {{ $errors->has('supporter_address') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">작업자주소</span>
  <input type="text" name="supporter_address" id="supporter_address" value="{{ old('supporter_address', ($row->supporter) ? $row->supporter->address : '') }}"
        class="form-control" readonly/>
  <div>{!! $errors->first('supporter_address', '<span class="form-error">:message</span>') !!}</div>
</div>

<div class="input-group input-group-lg {{ $errors->has('supporter_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <input type="hidden" name="supporter_id" id="supporter_id" value="{{ old('supporter_id', ($row->supporter) ? $row->supporter->id : '') }}" class="form-control"/>
</div>

<div class="input-group input-group-lg {{ $errors->has('job_start_date') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">작업시작일(*)</span>
  <input type="text" name="job_start_date" id="job_start_date" class="form-control datePicker" placeholder="작업시작일을 선택하세요"
  value="{{ old('job_start_date', ($row->job_start_date) ? $row->job_start_date->format('Y-m-d') : '') }}">
  {!! $errors->first('job_start_date', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('job_end_date') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">작업종료일(*)</span>
  <input type="text" name="job_end_date" id="job_end_date" class="form-control datePicker" placeholder="작업종료일을 선택하세요"
  value="{{ old('job_end_date', ($row->job_end_date) ? $row->job_end_date->format('Y-m-d') : '') }}">
  {!! $errors->first('job_end_date', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('work_detail') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">작업내용(*)</span>
  <input type="text" name="work_detail" id="work_detail" value="{{ old('work_detail', $row->work_detail) }}" class="form-control" placeholder="작업내용을 입력하세요" />
  {!! $errors->first('work_detail', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">제공자</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">지원단</span>
  <input type="radio" id="supporter" name="recipient" value="S" class="form-control" style="width:18px;" {{ !($row->recipient == 'F') ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">농가</span>
  <input type="radio" id="farm" name="recipient" value="F" class="form-control" style="width:18px;" {{ ($row->recipient == 'F') ? 'checked' : '' }}>
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_item1') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">교통비</span>
  <input type="number" name="payment_item1" id="payment_item1" value="{{ old('payment_item1', $row->payment_item1) }}" class="form-control" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_item1', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_item2') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">간식비</span>
  <input type="number" name="payment_item2" id="payment_item2" value="{{ old('payment_item2', $row->payment_item2) }}" class="form-control" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_item2', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_item3') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">마스크구입비</span>
  <input type="number" name="payment_item3" id="payment_item3" value="{{ old('payment_item3', $row->payment_item3) }}" class="form-control" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_item3', '<span class="form-error">:message</span>') !!}
</div>

@if ($viewName === 'status_manpower_supporters.edit')
<div class="input-group input-group-lg {{ $errors->has('payment_sum') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(합계)</span>
  <input type="number" name="payment_sum" id="payment_sum" value="{{ old('payment_sum', $row->payment_sum) }}" maxlength="11" style="font-weight:bold;" class="form-control" readonly/>
  {!! $errors->first('payment_sum', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_do') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(도비)(*)</span>
  <input type="number" name="payment_do" id="payment_do" value="{{ old('payment_do', $row->payment_do) }}" maxlength="11"
        class="form-control sum_payment" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_do', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_sigun') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(시군비)(*)</span>
  <input type="number" name="payment_sigun" id="payment_sigun" value="{{ old('payment_sigun', $row->payment_sigun) }}" maxlength="11"
        class="form-control sum_payment" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_sigun', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_center') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(중앙회)(*)</span>
  <input type="number" name="payment_center" id="payment_center" value="{{ old('payment_center', $row->payment_center) }}" maxlength="11"
        class="form-control sum_payment" placeholder="숫자만 입력하세요" numberOnly/>
  {!! $errors->first('payment_center', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_unit') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(지역농협)(*)</span>
  <input type="number" name="payment_unit" id="payment_unit" value="{{ old('payment_unit', $row->payment_unit) }}" maxlength="11"
        class="form-control sum_payment" placeholder="숫자만 입력하세요" numberOnly/>
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
      $('.datePicker').datepicker({
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
    });//ready end
  </script>
@stop
