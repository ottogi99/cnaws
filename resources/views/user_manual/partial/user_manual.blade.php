@if ($viewName == 'manual.show')
  @include('attachments.partial.list', ['attachments' => $manual->attachments])
@endif

<div class="media">
  <div class="media-body">
    <h4 class="media-heading">
      <a href="{{ route('user_manual.show', $manual->id )}}">
        {{ $manual->title }}
      </a>
    </h4>

    <p class="text-muted">
      <i class="fa fa-user"></i> {{ $manual->user->name }}
      <i class="fa fa-clock-o"></i> {{ $manual->created_at->diffForHumans() }}
    </p>
  </div>
</div>
