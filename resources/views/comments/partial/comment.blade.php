<div class="media item__comment {{ isReply ? 'sub' : 'top' }}" data-id="{{ $comment->id }}" id="comment_{{ $comment->id }}">
  <div class="media-body">
    <h5 class="media-heading">
      <span>{{ $comment->user->name }}</span>
      <small>{{ $comment->created_at->diffForHumans() }}</small>
    </h5>

    <div class="content__commnet">
      {!! $comment->content !!}
    </div>

    <div class="action_comment">
      @can('update', $comment)
        <button class="btn__delete_comment">댓글 삭제</button>
        <button class="btn__edit__comment">댓글 수정</button>
      @endcan

      @if ($currentUser)
        <button class="btn__reply__comment">답글 쓰기</button>
      @endif
    </div>

    @if ($currentUser)
      @include('comments.partial.create', ['parentId' => $comment->id])
    @endif

    @can('update', $comment)
      @include('comments.partial.edit')
    @endcan

    @forelse ($comment->replies as $replay)
      @include('comments.partial.comment', [
        'comment' => $reply,
        'isReply' => true,
      ])
    @empty
    @endforelse
  </div>
</div>
