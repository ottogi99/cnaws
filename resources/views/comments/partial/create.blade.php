<!--
<div class="media media__create__comment">
    <div class="media-body">
      <form method="POST" action="{{ route('suggestion.comments.store', $suggestion->id) }}" class="form-horizontal">
        @csrf
 -->
        <div class="input-group input-group-lg {{ $errors->has('content') ? 'has-error' : '' }}" style="padding-top:20px; border-top:2px solid #efefef;">
          <span class="input-group-addon" style="width:20%; font-size:13px; height:10px;">댓글</span>
          <input type="text" class="form-control" placeholder="내용을 입력하세요." style="height:40px;">
          <a id="#" href="#" class="btn btn-primary passclick" style="position:absolute; height:40px; line-height:20px;">등록</a>
          {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
        </div>

        <!-- <div class="form-group">
          <textarea name="content" class="form-control">{{ old('content') }}</textarea>
        </div> -->

        <!-- <div class="text-right">
          <button type="submit" class="btn btn-primary btn-sm">
            전송하기
          </button>
        </div>
      </form>
    </div>
</div> -->
