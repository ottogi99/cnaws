@if ($viewName == 'suggestion.show')
  @include('suggestion.partial.list', ['attachments' => $suggestion->attachments])
@endif

<div class="media">
  <div class="media-body">
    <h4 class="media-heading">
      <a href="{{ route('suggestion.show', $suggestion->id )}}">
        {{ $suggestion->title }}
      </a>
    </h4>

    <p class="text-muted">
      <i class="fa fa-user"></i> {{ $suggestion->user->name }}
      <i class="fa fa-clock-o"></i> {{ $suggestion->created_at->diffForHumans() }}
    </p>
  </div>
</div>
