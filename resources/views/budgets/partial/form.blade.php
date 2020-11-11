@if ($viewName === 'budgets.create')
  @if (auth()->user()->isAdmin())
  <div class="input-group input-group-lg {{ $errors->has('sigun_code') ? 'has-error' : '' }}" style="padding-bottom:10px;">
    <span class="input-group-addon" style="width:150px; font-size:13px;">시군명</span>
    <select name="sigun_code" id="sigun_code" >
      @forelse($siguns as $sigun)
        <option value="{{ $sigun->code }}" {{ ($sigun->code == auth()->user()->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
      @empty
        <option>-</option>
      @endforelse
    </select>
    {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
  </div>

  <div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
    <span class="input-group-addon" style="width:150px; font-size:13px;">대상농협</span>
    <select name="nonghyup_id" id="nonghyup_id" >
      @forelse($nonghyups as $nonghyup)
        <option value="{{ $nonghyup->nonghyup_id }}" {{ ($nonghyup->nonghyup_id == auth()->user()->nonghyup_id) ? 'selected="selected"' : '' }}>{{ $nonghyup->name }}</option>
      @empty
        <option>-</option>
      @endforelse
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

@if ($viewName === 'budgets.edit')
<div class="input-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
  <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $budget->sigun_code) }}" class="form-control" readonly/>
</div>
<div class="input-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
  <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $budget->nonghyup_id) }}" class="form-control" readonly/>
</div>
@endif

<div class="input-group input-group-lg {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">사업년도</span>
  <select name="business_year" id="business_year" {{ auth()->user()->isAdmin() ? '' : 'disabled' }}>
    @for($year = now()->year; $year >= 2019; $year--)
      <option value="{{ $year }}" {{ ($budget->business_year == $year) ? 'selected="selected"' : '' }}>{{ $year }}</option>
    @endfor
  </select>
  {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
</div>


@if ($viewName === 'budgets.create')
<div class="input-group input-group-lg {{ $errors->has('amount') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">사업비(원)(*)</span>
  <input type="number" name="amount" id="amount" value="{{ old('amount', $budget->amount) }}" class="form-control"/>
  {!! $errors->first('amount', '<span class="form-error">:message</span>') !!}
</div>
@else
<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">사업비(원)</span>
  <input type="number" name="amount" id="amount" value="{{ old('amount', $budget->amount) }}" class="form-control" style="font-weight:bold;" numberOnly readonly/>
  {!! $errors->first('amount', '<span class="form-error">:message</span>') !!}
</div>
@endif

@if ($viewName === 'budgets.edit')
<div class="input-group input-group-lg {{ $errors->has('payment_do') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(도비)(*)</span>
  <input type="number" name="payment_do" id="payment_do" value="{{ old('payment_do', $budget->payment_do) }}" maxlength="11" class="form-control sum_payment" numberOnly/>
  {!! $errors->first('payment_do', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_sigun') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(시군비)(*)</span>
  <input type="number" name="payment_sigun" id="payment_sigun" value="{{ old('payment_sigun', $budget->payment_sigun) }}" maxlength="11" class="form-control sum_payment" numberOnly/>
  {!! $errors->first('payment_sigun', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_center') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(중앙회)(*)</span>
  <input type="number" name="payment_center" id="payment_center" value="{{ old('payment_center', $budget->payment_center) }}" maxlength="11" class="form-control sum_payment" numberOnly/>
  {!! $errors->first('payment_center', '<span class="form-error">:message</span>') !!}
</div>

<div class="input-group input-group-lg {{ $errors->has('payment_unit') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">지급액(지역농협)(*)</span>
  <input type="number" name="payment_unit" id="payment_unit" value="{{ old('payment_unit', $budget->payment_unit) }}" maxlength="11" class="form-control sum_payment" numberOnly/>
  {!! $errors->first('payment_unit', '<span class="form-error">:message</span>') !!}
</div>
@endif
