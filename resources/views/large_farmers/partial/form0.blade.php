@if ($viewName === 'large_farmers.create')
    <div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
        <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', auth()->user()->sigun->code) }}" class="form-control" readonly/>
    </div>

    @if (auth()->user()->isAdmin())
      <div class="input-group">
        <label for="nonghyup_id">농협명</label>
        <select class="form-control" name="nonghyup_id" id="nonghyup_id">
          {!! options_for_nonghyup($nonghyups, '', true, true) !!}
        </select>
      </div>
    @else
      <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
          <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', auth()->user()->nonghyup_id) }}" class="form-control" readonly/>
      </div>
    @endif
@endif

@if ($viewName === 'large_farmers.edit')
    <div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
        <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $farmer->sigun->code) }}" class="form-control" readonly/>
    </div>
    <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
        <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $farmer->nonghyup_id) }}" class="form-control" readonly/>
    </div>
@endif

<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="content">성명</label>
    <input type="text" name="name" id="name" value="{{ old('name', $farmer->name) }}" class="form-control"/>
    {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
    <label for="content">연령(세)</label>
    <input type="number" name="age" id="age" value="{{ old('age', $farmer->age) }}" class="form-control" numberOnly/>
    {!! $errors->first('age', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
    <input type="radio" id="male" name="sex" value="M" {{ !($farmer->sex == 'F') ? 'checked' : '' }}>
    <label for="male">남</label><br>
    <input type="radio" id="female" name="sex" value="F" {{ ($farmer->sex == 'F') ? 'checked' : '' }}>
    <label for="female">여</label><br>
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address">주소</label>
    <input type="text" name="address" id="address" value="{{ old('address', $farmer->address) }}" class="form-control" />
    {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
    <input type="button" value="주소검색" onclick="openAddrPopup();">
</div>

<div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="contact">연락처</label>
    <input type="number" name="contact" id="contact" value="{{ old('contact', $farmer->contact) }}" maxlength="11" class="form-control" numberOnly/>
    {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage') ? 'has-error' : '' }}">
    <label for="acreage">소유경지면적(ha)</label>
    <input type="number" step="0.01" name="acreage" id="acreage" value="{{ old('acreage', $farmer->acreage) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('cultivar') ? 'has-error' : '' }}">
    <label for="cultivar">재배품목</label>
    <input type="text" name="cultivar" id="cultivar" value="{{ old('cultivar', $farmer->cultivar) }}" class="form-control"/>
    {!! $errors->first('cultivar', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
    <label for="bank_name">은행명</label>
    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $farmer->bank_name) }}" class="form-control" />
    {!! $errors->first('bank_name', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('bank_account') ? 'has-error' : '' }}">
    <label for="bank_account">계좌번호</label>
    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $farmer->bank_account) }}" class="form-control"/>
    {!! $errors->first('bank_account', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="remark">비고</label>
  <textarea class="form-control rounded-0" name="remark" id="remark" rows="3">{{ old('remark', $farmer->remark) }}</textarea>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>
