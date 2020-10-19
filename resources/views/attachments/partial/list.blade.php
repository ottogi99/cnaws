@if ($attachments->count())
  <ul class="attachment__article">
    <li><i class="fa fa-paperclip"></i></li>
    @foreach ($attchments as $attachment)
    <li>
      <a href="{{ $attachment->url }}">
        {{ $attachement->filename }} ({{ $attachement->bytes }})
      </a>
    </li>
    @endforeach
  </ul>
@endif
