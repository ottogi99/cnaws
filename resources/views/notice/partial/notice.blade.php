@if ($viewName == 'notice.show')
  @include('attachments.partial.list', ['attachments' => $notice->attachments])
@endif

<div class="media">
  <div class="media-body">
    <h4 class="media-heading">
      <a href="{{ route('notice.show', $notice->id )}}">
        {{ $notice->title }}
      </a>
    </h4>

    <p class="text-muted">
      <i class="fa fa-user"></i> {{ $notice->user->name }}
      <i class="fa fa-clock-o"></i> {{ $notice->created_at->diffForHumans() }}
    </p>
  </div>
</div>
