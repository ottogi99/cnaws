<form class="form-inline" method="get" action="{{ route('performance_executive.index') }}" role="search">
    <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
      <label for="year">대상년도</label>
      <select class="form-control" name="year" id="year" >
        {!! options_for_year(request()->input('year')) !!}
      </select>
    </div>

    <div class="input-group">
      <label for="sigun_code">시군명</label>
      <select class="form-control" name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, request()->input('sigun_code')) !!}
      </select>
    </div>

    <div class="input-group">
      <label for="nonghyup_id">농협명</label>
      <select class="form-control" name="nonghyup_id" id="nonghyup_id">
        {!! options_for_nonghyup($nonghyups, request()->input('nonghyup_id'), true) !!}
      </select>
    </div>

    <div class="input-group">
      <button class="btn btn-secondary" type="submit">
        <i class="fas fa-search text-grey"aria-hidden="true"></i>
      </button>
    </div>
</form>
