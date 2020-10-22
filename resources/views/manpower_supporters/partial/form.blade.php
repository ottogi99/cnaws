@if ($viewName === 'manpower_supporters.create')
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

@if ($viewName === 'manpower_supporters.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $supporter->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $supporter->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성명</span>
  <input type="text" name="name" id="name" value="{{ old('name', $supporter->name) }}" class="form-control"/>
  {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연령(세)</span>
  <input type="number" name="age" id="age" value="{{ old('age', $supporter->age) }}" class="form-control" numberOnly/>
  {!! $errors->first('age', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성별</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">남자</span>
  <input type="radio" id="male" name="sex" value="M" class="form-control" style="width:18px;" {{ !($supporter->sex == 'F') ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">여자</span>
  <input type="radio" id="female" name="sex" value="F" class="form-control" style="width:18px;" {{ ($supporter->sex == 'F') ? 'checked' : '' }}>
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
  <input type="text" name="address" id="address" value="{{ old('address', $supporter->address) }}" class="form-control" />
  <input type="button" value="도로명주소 검색" onclick="openAddrPopup();">
  {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('contact') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
  <input type="number" name="contact" id="contact" value="{{ old('contact', $supporter->contact) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('training_date1') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일1</span>
  <input type="text" name="training_date1" id="training_date1" value="{{ old('training_date1', $supporter->training_date1) }}" class="form-control" />
  {!! $errors->first('training_date1', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('training_date2') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일2</span>
  <input type="text" name="training_date2" id="training_date2" value="{{ old('training_date2', $supporter->training_date2) }}" class="form-control" />
  {!! $errors->first('training_date2', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('training_date2') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">교육참여일3</span>
  <input type="text" name="training_date3" id="training_date3" value="{{ old('training_date3', $supporter->training_date3) }}" class="form-control" />
  {!! $errors->first('training_date3', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">상해보험 가입여부</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">가입</span>
  <input type="radio" id="join" name="has_insurance" class="form-control" style="width:18px;" value="1" {{ ($supporter->has_insurance) ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">미가입</span>
  <input type="radio" id="not_join" name="has_insurance" class="form-control" style="width:18px;" value="0" {{ (!$supporter->has_insurance) ? 'checked' : '' }}>
  {!! $errors->first('has_insurance', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('bank_name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">은행명</span>
  <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $supporter->bank_name) }}" class="form-control" />
  {!! $errors->first('bank_name', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('bank_account') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">계좌번호</span>
  <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $supporter->bank_account) }}" class="form-control"/>
  {!! $errors->first('bank_account', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('remark') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
  <input type="text" name="remark" id="remark" value="{{ old('remark', $supporter->remark) }}" class="form-control"/>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>
