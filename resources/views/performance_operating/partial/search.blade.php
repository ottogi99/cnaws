<form class="form-inline" method="get" action="{{ route('performance_operating.index') }}" role="search">
  <button class="btn btn-primary passclick pull-right" style="height:35px; line-height:17px; margin-bottom:20px;">검색</button>
  <select name="nonghyup_id" id="nonghyup_id" >
    {!! options_for_nonghyup($nonghyups, request()->input('nonghyup_id'), auth()->user()) !!}
  </select>
  <select name="sigun_code" id="sigun_code">
    {!! options_for_sigun($siguns, request()->input('sigun_code')) !!}
  </select>
  <select name="year" id="year" >
    {!! options_for_year(request()->input('year')) !!}
  </select>
</form>
