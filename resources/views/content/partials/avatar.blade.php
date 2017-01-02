@php
    $author = $content->author();
    $authorName = $author ? $author->name : 'No author specified';
    $profileImage = $author ? $author->present()->profile_image : \App\User::DEFAULT_PROFILE_IMAGE;
@endphp
<img src="{{ $profileImage }}" class='create-image' alt="{{ $authorName }}" title="{{ $authorName }}">
