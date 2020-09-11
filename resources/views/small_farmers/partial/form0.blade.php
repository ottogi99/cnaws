@if ($viewName === 'small_farmers.create')
    <div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
        <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', auth()->user()->sigun->code) }}" class="form-control" readonly/>
    </div>

    @if (auth()->user()->isAdmin())
      <div class="form-group">
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

@if ($viewName === 'small_farmers.edit')
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

<div class="form-group {{ $errors->has('acreage1') ? 'has-error' : '' }}">
    <label for="acreage1">답작</label>
    <input type="number" name="acreage1" id="acreage1" value="{{ old('acreage1', $farmer->acreage1) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage1', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage2') ? 'has-error' : '' }}">
    <label for="acreage2">전작</label>
    <input type="number" name="acreage2" id="acreage2" value="{{ old('acreage2', $farmer->acreage2) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage2', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage3') ? 'has-error' : '' }}">
    <label for="acreage3">기타</label>
    <input type="number" name="acreage3" id="acreage3" value="{{ old('acreage3', $farmer->acreage3) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage3', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="remark">비고</label>
  <textarea class="form-control rounded-0" name="remark" id="remark" rows="3">{{ old('remark', $farmer->remark) }}</textarea>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>
