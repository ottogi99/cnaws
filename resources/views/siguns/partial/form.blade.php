<div class="form-group {{ $errors->has('seq') ? 'has-error' : '' }}">
    <label for="content">순번</label>
    <input type="number" name="sequence" id="sequence" min="1" max="99" value="{{ old('sequence', $sigun->sequence) }}" class="form-control" />
    {!! $errors->first('sequence', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
    <label for="content">시군코드</label>
    <input type="text" name="code" id="code" value="{{ old('code', $sigun->code) }}" class="form-control" />
    {!! $errors->first('code', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="content">시군명</label>
    <input type="text" name="name" id="name" value="{{ old('name', $sigun->name) }}" class="form-control" />
    {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>
