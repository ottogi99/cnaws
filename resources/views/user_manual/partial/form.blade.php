<!-- 시작 -->
<div class="input-group input-group-lg {{ $errors->has('title') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:20%; font-size:13px;">제목</span>
  <input type="text" name="title" id="title" value="{{ old('title', $manual->title) }}" class="form-control" placeholder="제목을 입력하세요">
  <div>{!! $errors->first('title', '<span class="form-error">:message</span>') !!}</div>
</div>
<div class="input-group input-group-lg {{ $errors->has('content') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:20%; font-size:13px;">내용</span>
  <textarea class="form-control" name="content" id="content">{{ old('content', $manual->content) }}</textarea>
  <div>{!! $errors->first('content', '<span class="form-error">:message</span>') !!}</div>
</div>

@if ($viewName == 'user_manual.edit')
<div class="input-group input-group-lg" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:20%; font-size:13px;">첨부파일</span>
  @forelse ($manual->attachments as $attachement)
  <div style="padding-bottom:10px; border:1px; font-size:12px;" id="attachment_{{ $attachement->id }}">{{ $attachement->original_name }}
    <a href="#" class="btn btn-round btn-default attachment__delete" data-id="{{ $attachement->id }}">X</a>
  </div>
  @empty
  <div style="padding-bottom:10px; border:1px; font-size:12px;">
  </div>
  @endforelse
</div>
@endif

<div class="input-group input-group-lg {{ $errors->has('files') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:20%; font-size:13px;">파일첨부</span>
  <input type="file" name="files[]" id="files" class="form-control" multiple="multiple"/>
  <div>{!! $errors->first('files.0', '<span class="form-error">:message</span>') !!}</div>
</div>
<!--
<div class="input-group input-group-lg {{ $errors->has('attach') ? 'has-error' : '' }}" style="padding-bottom:10px;">
  <span class="input-group-addon" style="width:150px; font-size:13px;">파일첨부</span>
  <input data-no-uniform="true" type="file" name="file_upload" id="file_upload" style="padding-bottom:10px;">
  <input data-no-uniform="true" type="file" name="file_upload" id="file_upload" style="padding-bottom:10px;">
  <input data-no-uniform="true" type="file" name="file_upload" id="file_upload">
</div>
 -->
