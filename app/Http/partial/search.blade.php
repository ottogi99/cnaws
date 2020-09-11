<form method="get" action="{{ route('users.index') }}" role="search">
    <!-- <input type="text" name="q" class="form-control" placeholder="검색어"/> -->
    <select name="qs">
      <option value="">전체</option>
      @forelse($siguns as $sigun)
        <option value="{{ $sigun->code }}">{{ $sigun->name }}</option>
      @endforeach
    </select>
    <select name="qu">
      <option value="">전체</option>
      @forelse($nonghyups as $nonghyup)
        <option value="{{ $nonghyup->user_id }}">{{ $nonghyup->name }}</option>
      @endforeach
    </select>
    <input class="btn btn-info btn-sm" type="submit" value="검색">
</form>
