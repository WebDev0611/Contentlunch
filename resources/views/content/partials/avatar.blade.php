@if(isset($content))
<img src="{{ $content->present()->authorProfileImage }}"
     class='create-image'
     alt="{{ $content->present()->authorName }}"
     title="{{ $content->present()->authorName }}">
@endif
