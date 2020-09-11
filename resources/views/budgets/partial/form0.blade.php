<div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
  <label for="sigun">시군명</label>
  <select name="sigun_code" id="sigun_code" >
    @forelse($siguns as $sigun)
      <option value="{{ $sigun->code }}" {{ ($sigun->code == $budget->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('nh_id') ? 'has-error' : '' }}">
  <label for="nonghyup">농협명</label>
  <select name="nh_id" id="nh_id" >
    @forelse($nonghyups as $nonghyup)
      <option value="{{ $nonghyup->user_id }}" {{ ($nonghyup->nonghyup_id == $budget->nonghyup_id) ? 'selected="selected"' : '' }}>{{ $nonghyup->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('nh_id', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
  <label for="year">사업년도</label>
  <select name="year" id="year" >
    @for($year = 2020; $year <= now()->year; $year++)
      <option value="{{ $year }}" {{ ($budget->business_year == now()->year) ? 'selected="selected"' : '' }}>{{ $year }}</option>
    @endfor
  </select>
  {!! $errors->first('year', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
  <label for="amount">사업비<small>(단위:만원)</small></label>
  <input type="text" name="amount" id="amount" value="{{ old('amount', $budget->amount) }}" class="form-control" />
  {!! $errors->first('amount', '<span class="form-error">:message</span>') !!}
</div>
