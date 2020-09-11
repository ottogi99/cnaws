@if ($viewName === 'small_farmers.create')
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

@if ($viewName === 'small_farmers.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $farmer->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $farmer->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성명</span>
  <input type="text" name="name" id="name" value="{{ old('name', $farmer->name) }}" class="form-control"/>
  {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연령(세)</span>
  <input type="number" name="age" id="age" value="{{ old('age', $farmer->age) }}" class="form-control" numberOnly/>
  {!! $errors->first('age', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">성별</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">남자</span>
  <input type="radio" id="male" name="sex" value="M" class="form-control" style="width:18px;" {{ !($farmer->sex == 'F') ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">여자</span>
  <input type="radio" id="female" name="sex" value="F" class="form-control" style="width:18px;" {{ ($farmer->sex == 'F') ? 'checked' : '' }}>
</div>

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
  <input type="text" name="address" id="address" value="{{ old('address', $farmer->address) }}" class="form-control" />
  <input type="button" value="도로명주소 검색" onclick="openAddrPopup();">
  {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('contact') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
  <input type="number" name="contact" id="contact" value="{{ old('contact', $farmer->contact) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('acreage1') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">답작</span>
  <input type="number" name="acreage1" id="acreage1" value="{{ old('acreage1', $farmer->acreage1) }}" class="form-control" numberOnly/>
  {!! $errors->first('acreage1', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('acreage2') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">전작</span>
  <input type="number" name="acreage2" id="acreage2" value="{{ old('acreage2', $farmer->acreage2) }}" class="form-control" numberOnly/>
  {!! $errors->first('acreage2', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('acreage3') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">기타</span>
  <input type="number" name="acreage3" id="acreage3" value="{{ old('acreage3', $farmer->acreage3) }}" class="form-control" numberOnly/>
  {!! $errors->first('acreage3', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('remark') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비고</span>
  <input type="text" name="remark" id="remark" value="{{ old('remark', $farmer->remark) }}" class="form-control"/>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>
