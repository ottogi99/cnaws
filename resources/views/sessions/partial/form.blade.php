<div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
  <label for="sigun_code">시군명</label>
  <select name="sigun_code" id="sigun_code" >
    @forelse($siguns as $sigun)
      <option value="{{ $sigun->code }}" {{ ($sigun->code == $user->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
    <label for="content">농협ID</label>
    <input
      type="text" name="nonghyup_id" id="nonghyup_id" placeholder="사용자(농협) ID" value="{{ old('nonghyup_id', $user->nonghyup_id) }}" class="form-control"
      {{ ($user->nonghyup_id) ? 'readonly' : '' }}/>
    {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="content">비밀번호</label>
    <input type="password" name="password" id="password" placeholder="비밀번호" value="" class="form-control" />
    {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password_confirmation">비밀번호</label>
    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="비밀번호 확인" value="" class="form-control" />
    {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
</div>

권한:
<div class="form-group">
    <input type="radio" id="general" name="is_admin" value="0" {{ ($user->is_admin) ? '' : 'checked' }}>
    <label for="general">일반(농협)</label><br>
    <input type="radio" id="admin" name="is_admin" value="1" {{ ($user->is_admin) ? 'checked' : '' }}>
    <label for="admin">관리자</label><br>
</div>
{!! $errors->first('is_admin', '<span class="form-error">:is_admin</span>') !!}

@if ($viewName == 'users.edit')
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="content">농협명</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" />
    {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="content">도로명 주소</label>
    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="form-control" />
    {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
    <input type="button" value="주소검색" onclick="goPopup();">
</div>

<div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="content">연락처</label>
    <input type="text" name="contact" id="contact" value="{{ old('contact', $user->contact) }}" maxlength="11" class="form-control" numberOnly/>
    {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('representative') ? 'has-error' : '' }}">
    <label for="content">대표자</label>
    <input type="text" name="representative" id="representative" value="{{ old('representative', $user->representative) }}" class="form-control" />
    {!! $errors->first('representative', '<span class="form-error">:message</span>') !!}
</div>
@endif

@section('script')
  @parent
  <script type="text/javascript">
    window.onload = function() {
    		jusoCallBack('roadFullAddr');
    }

    function jusoCallBack(roadFullAddr){
      if (roadFullAddr != 'roadFullAddr')
    	 document.getElementById('address').value = roadFullAddr;
    }

    function goPopup(){
    	var pop = window.open("/apis/popup","pop","width=570,height=420, scrollbars=yes, resizable=yes");
    }
  </script>
@stop
