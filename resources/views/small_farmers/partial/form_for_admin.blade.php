<div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
  <label for="sigun_code">시군명</label>
  <select name="sigun_code" id="sigun_code" >
    @forelse($siguns as $sigun)
      <option value="{{ $sigun->code }}" {{ ($sigun->code == $farmer->sigun_code) ? 'selected="selected"' : '' }}>{{ $sigun->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
</div>

<!-- 관리자인 경우에는 모든 탭 선택이 가능하도록 -->
<div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
  <label for="nonghyups">대상농협</label>
  <select name="nonghyup_id" id="nonghyup_id" >
    @forelse($nonghyup as $nonghyups)
      <option value="{{ $nonghyup->user_id }}" {{ ($nonghyup->user_id == $user->user_id) ? 'selected="selected"' : '' }}>{{ $nonghyup->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
</div>

<!-- 사용자(농협)인 경우 자신의 값이 기본으로 선택되고 변경할 수 없도록 -->
<div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
  <label for="nonghyups">대상농협</label>
  <select name="nonghyup_id" id="nonghyup_id" readOnly>
    @forelse($nonghyups as $nonghyup)
      <option value="{{ $nonghyup->user_id }}" {{ ($nonghyup->user_id == $user->user_id) ? 'selected="selected"' : '' }}>{{ $nonghyup->name }}</option>
    @empty
      <option>-</option>
    @endforelse
  </select>
  {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('nh_id') ? 'has-error' : '' }}">
    <label for="content">대상농협ID</label>
    <input type="text" name="nonghyup_id" id="nh_id" value="{{ old('nonghyup_id', $farmer->nonghyup_id) }}" class="form-control" readonly/>
</div>

<div class="form-group {{ $errors->has('nh_id') ? 'has-error' : '' }}">
    <label for="content">대상농협명</label>
    <input type="text" value="" class="form-control" disabled/>
</div>

<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="content">성명</label>
    <input type="text" name="name" id="name" value="{{ old('name', $farmer->name) }}" class="form-control"/>
    {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
    <label for="content">연령(세)</label>
    <input type="number" name="age" id="age" value="{{ old('age', $farmer->age) }}" class="form-control" numberOnly/>
    {!! $errors->first('age', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
    <input type="radio" id="male" name="sex" value="M" {{ ($farmer->sex == 'M') ? 'checked' : '' }}>
    <label for="male">남</label><br>
    <input type="radio" id="female" name="sex" value="F" {{ ($farmer->sex == 'F') ? 'checked' : '' }}>
    <label for="female">여</label><br>
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address">주소</label>
    <input type="text" name="address" id="address" value="{{ old('address', $farmer->address) }}" class="form-control" />
    {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
    <input type="button" value="주소검색" onclick="goPopup();">
</div>

<div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="contact">연락처</label>
    <input type="number" name="contact" id="contact" value="{{ old('contact', $farmer->contact) }}" maxlength="11" class="form-control" numberOnly/>
    {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage1') ? 'has-error' : '' }}">
    <label for="acreage1">답작</label>
    <input type="number" name="acreage1" id="acreage1" value="{{ old('acreage1', $farmer->acreage1) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage1', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage2') ? 'has-error' : '' }}">
    <label for="acreage2">전작</label>
    <input type="number" name="acreage2" id="acreage2" value="{{ old('acreage2', $farmer->acreage2) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage2', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('acreage3') ? 'has-error' : '' }}">
    <label for="acreage3">기타</label>
    <input type="number" name="acreage3" id="acreage3" value="{{ old('acreage3', $farmer->acreage3) }}" class="form-control" numberOnly/>
    {!! $errors->first('acreage3', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="remark">비고</label>
  <textarea class="form-control rounded-0" name="remark" id="remark" rows="3">{{ old('remark', $farmer->remark) }}</textarea>
  {!! $errors->first('remark', '<span class="form-error">:message</span>') !!}
</div>

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
