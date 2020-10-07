@if ($viewName === 'users.create')
  @if (auth()->user()->isAdmin())
    <div class="input-group input-group-lg {{ $errors->has('sigun_code') ? 'has-error' : '' }}" style="padding-bottom:10px;">
      <span class="input-group-addon" style="width:150px; font-size:13px;">시군명</span>
      <select name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, request()->input('sigun_code'), true) !!}
      </select>
      {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
      <span class="input-group-addon" style="width:150px; font-size:13px;">농협ID</span>
      <input type="text" name="nonghyup_id" id="nonghyup_id" placeholder="사용자(농협) ID"
              value="{{ old('nonghyup_id', $nonghyup->nonghyup_id) }}" class="form-control" {{ ($nonghyup->nonghyup_id) ? 'readonly' : '' }}/>
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

@if ($viewName === 'users.edit')
  <div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
    <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $nonghyup->sigun->code) }}" class="form-control" readonly/>
  </div>
  <div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $nonghyup->nonghyup_id) }}" class="form-control" readonly/>
  </div>
@endif

<div class="input-group input-group-lg {{ $errors->has('name') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">농협명</span>
  <input type="text" name="name" id="name" value="{{ old('name', $nonghyup->name) }}" class="form-control" />
  {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

@if ($viewName === 'users.create')
<div class="input-group input-group-lg {{ $errors->has('password') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비밀번호</span>
  <input type="password" name="password" id="password" placeholder="비밀번호" value="" class="form-control" />
  {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('password_confirmation') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비밀번호 확인</span>
  <input type="password" name="password_confirmation" id="password_confirmation" placeholder="비밀번호 확인" value="" class="form-control" />
  {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
</div>
@endif

@if (auth()->user()->isAdmin())
<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">권한</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">일반(농협)</span>
  <input type="radio" id="general" name="is_admin" value="0" class="form-control" style="width:18px;" {{ ($nonghyup->is_admin) ? '' : 'checked' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">관리자</span>
  <input type="radio" id="admin" name="is_admin" value="1" class="form-control" style="width:18px;" {{ ($nonghyup->is_admin) ? 'checked' : '' }}>
</div>
{!! $errors->first('is_admin', '<span class="form-error">:is_admin</span>') !!}

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">계정상태</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">활성</span>
  <input type="radio" id="activated" name="activated" value="1" class="form-control" style="width:18px;" {{ ($nonghyup->activated) ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">비활성</span>
  <input type="radio" id="deactivated" name="activated" value="0" class="form-control" style="width:18px;" {{ ($nonghyup->activated) ? '' : 'checked' }}>
</div>
{!! $errors->first('activated', '<span class="form-error">:activated</span>') !!}

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">입력상태</span>
  <span class="input-group-addon" style="width:41px; font-size:13px;">가능</span>
  <input type="radio" id="allowed" name="is_input_allowed" value="1" class="form-control" style="width:18px;" {{ ($nonghyup->is_input_allowed) ? 'checked' : '' }}>
  <span class="input-group-addon" style="width:41px; font-size:13px;">중지</span>
  <input type="radio" id="blocked" name="is_input_allowed" value="0" class="form-control" style="width:18px;" {{ ($nonghyup->is_input_allowed) ? '' : 'checked' }}>
</div>
{!! $errors->first('is_input_allowed', '<span class="form-error">:is_input_allowed</span>') !!}
@endif

<div class="input-group input-group-lg {{ $errors->has('address') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">주소</span>
  <input type="text" name="address" id="address" value="{{ old('address', $nonghyup->address) }}" class="form-control" />
  <input type="button" value="도로명주소 검색" onclick="openAddrPopup();">
  {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
</div>

@if ($viewName == 'users.edit')
<div class="input-group input-group-lg {{ $errors->has('contact') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">연락처</span>
  <input type="number" name="contact" id="contact" value="{{ old('contact', $nonghyup->contact) }}" maxlength="11" class="form-control" numberOnly/>
  {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('representative') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">대표자</span>
  <input type="text" name="representative" id="representative" value="{{ old('representative', $nonghyup->representative) }}" class="form-control" />
  {!! $errors->first('representative', '<span class="form-error">:message</span>') !!}
</div>
@endif

<div class="input-group input-group-lg">
  <span class="input-group-addon" style="width:150px; font-size:13px;">비밀번호</span>
  <button id="adminname" class="btn btn-default btn-setting" onclick="location.href='{{ route("users.password", $nonghyup->id) }}'" style="background-color:#323333; border:1px solid #ffffff; background-image:none;">
      <span class="hidden-sm hidden-xs" style="color:#ffffff;">비밀번호 변경</span></a>
  </button>
</div>
