@if (auth()->user()->isAdmin())
  @if ($viewName === 'budgets.create')
  <div class="input-group input-group-lg {{ $errors->has('sigun_code') ? 'has-error' : '' }}" style="padding-bottom:10px;">
    <span class="input-group-addon" style="width:150px; font-size:13px;">시군명</span>
    <select name="sigun_code" id="sigun_code" >
      @forelse($siguns as $sigun)
        <option value="{{ $sigun->code }}" {{ ($sigun->code == $budget->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
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
        <option value="{{ $nonghyup->nonghyup_id }}" {{ ($nonghyup->nonghyup_id == $budget->nonghyup_id) ? 'selected="selected"' : '' }}>{{ $nonghyup->name }}</option>
      @empty
        <option>-</option>
      @endforelse
    </select>
    {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
  </div>
  @endif
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

<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">사업비(원)</span>
  <input type="text" name="amount" id="amount" value="{{ old('amount', $budget->amount) }}" class="form-control" />
  {!! $errors->first('amount', '<span class="form-error">:message</span>') !!}
</div>
