@php
    $author = $content->author();
    $avatar = $author ? $author->profile_image : \App\User::DEFAULT_PROFILE_IMAGE;
    $authorName = $author ? $author->name : 'No author specified';
@endphp
<img src="{{ $avatar }}" class='create-image' alt="{{ $authorName }}" title="{{ $authorName }}">
