<form class="form-inline" method="get" action="{{ route('budgets.index') }}" role="search">
  <div class="input-group" style="float:right; margin-right:52px; height:35px; margin-bottom:20px;">
    @if (auth()->user()->isAdmin())
    <input
        type="text"
        name="q"
        class="form-control"
        placeholder="검색어를 입력하세요."
        style="background-color:#efefef; font-size:15px; width:230px;"
    />
    @endif
    <button class="btn btn-primary passclick" style="position:absolute; line-height:17px;">검색</button>
  </div>
  <select name="nonghyup_id" id="nonghyup_id" disabled {{ auth()->user()->isAdmin() ? '' : 'style=display:none' }} >
    {!! options_for_nonghyup($nonghyups, request()->input('nonghyup_id'), auth()->user()) !!}
  </select>
  <select name="sigun_code" id="sigun_code" {{ auth()->user()->isAdmin() ? '' : 'style=display:none' }} >
    {!! options_for_sigun($siguns, request()->input('sigun_code')) !!}
  </select>
  <select name="year" id="year" >
    {!! options_for_year(request()->input('year')) !!}
  </select>
</form>
