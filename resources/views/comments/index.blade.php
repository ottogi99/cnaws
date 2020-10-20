@php
  $currentUser = auth()->user();
  $comments = $suggestion->comments();
@endphp

<div class="form__new__comment">
  @include('comments.partial.create')
</div>

<div class="list__comment">
  @forelse($comments as $comment)
    @include('comments.partial.comment', [
      'parentId' => $comment->id,
      'isReplay' => false,
    ])
  @empty
  @endforelse
</div>
