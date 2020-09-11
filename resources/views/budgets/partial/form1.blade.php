@if (auth()->user()->isAdmin())
@if ($viewName === 'budgets.create')
<div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
  <label for="sigun_code">시군명</label>
  <select name="sigun_code" id="sigun_code" >
    @forelse($siguns as $sigun)
      <option value="{{ $sigun->code }}" {{ ($sigun->code == $budget->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
  <label for="nonghyup">농협명</label>
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

<div class="form-group {{ $errors->has('business_year') ? 'has-error' : '' }}">
  <label for="business_year">사업년도</label>
  <select name="business_year" id="business_year" {{ auth()->user()->isAdmin() ? '' : 'disabled' }}>
    @for($year = now()->year; $year >= 2019; $year--)
      <option value="{{ $year }}" {{ ($budget->business_year == $year) ? 'selected="selected"' : '' }}>{{ $year }}</option>
    @endfor
  </select>
  {!! $errors->first('business_year', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
  <label for="amount">사업비<small>(단위:만원)</small></label>
  <input type="text" name="amount" id="amount" value="{{ old('amount', $budget->amount) }}" class="form-control" />
  {!! $errors->first('amount', '<span class="form-error">:message</span>') !!}
</div>
