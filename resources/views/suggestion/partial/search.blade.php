<form class="form-inline" method="get" action="{{ route('suggestion.index') }}" role="search">
  <div class="input-group" style="float:right; margin-right:52px; margin-bottom:20px;">
    <input type="text" name="q" class="form-control" placeholder="제목 또는 작성자를 입력하세요" style="background-color:#efefef; font-size:15px; width:230px; height:35px;">
    <button class="btn btn-primary passclick" style="position:absolute; height:35px; line-height:17px;">검색</button>
  </div>
</form>
