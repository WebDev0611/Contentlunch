<div class="sidepanel-body">
    <div class="pane-users">
        <ul class="list-unstyled list-users">
            @foreach ($guests as $guest)
                <li>
                    <a href="#">
                        <div class="user-avatar">
                            <img src="/images/cl-avatar2.png" alt="#">
                        </div>
                        <p class="title">{{ $guest->name }}</p>
                        <p class="email">{{ $guest->email }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
