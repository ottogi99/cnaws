<form class="form-inline" method="get" action="{{ route('status_machine_supporters.index') }}" role="search">
    <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
      <label for="year">대상년도</label>
      <select class="form-control" name="year" id="year" >
        {!! options_for_year(request()->input('year')) !!}
      </select>
      {!! $errors->first('year', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="input-group" {{ auth()->user()->isAdmin() ? '' : 'style=display:none' }}>
      <label for="sigun_code">시군명</label>
      <select class="form-control" name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, request()->input('sigun_code')) !!}
      </select>
    </div>

    <div class="input-group">
      <label for="nonghyup_id">농협명</label>
      <select class="form-control" name="nonghyup_id" id="nonghyup_id">
        {!! options_for_nonghyup($nonghyups, request()->input('nonghyup_id'), auth()->user()) !!}
      </select>
    </div>

    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="키워드(성명, 주소, 비고)">
      <div class="input-group-append">
        <button class="btn btn-secondary" type="submit">
          <i class="fas fa-search text-grey"aria-hidden="true"></i>
        </button>
      </div>
    </div>
</form>
