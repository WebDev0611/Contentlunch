<div class="sidepanel-body">
    <div class="pane-users">
        <ul class="list-unstyled list-users">
            @foreach ($content->authors as $author)
                <li>
                    <a href="#">
                        <div class="user-avatar">
                            @if ($author->profile_image)
                                <img src="{{ $author->profile_image }}" alt="#">
                            @else
                                <img src="/images/avatar.jpg" alt="#">
                            @endif
                        </div>
                        <p class="title">{{ $author->name }}</p>
                        <p class="email">{{ $author->email }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>