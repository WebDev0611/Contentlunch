@php
    $author = $content->author();
    $authorName = $author ? $author->name : 'No author specified';
@endphp
<img src="{{ $author->present()->profile_image }}" class='create-image' alt="{{ $authorName }}" title="{{ $authorName }}">
