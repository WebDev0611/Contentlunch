<div class="sidepanel-body">
    <div class="pane-users">
        <ul class="list-unstyled list-users">
            @for ($i = 0; $i < 10; $i++)
            <li>
                <a href="#">
                    <div class="user-avatar">
                        <img src="/images/cl-avatar2.png" alt="#">
                    </div>
                    <p class="title">Jason Simmons</p>
                    <p class="email">jasonsimm@google.com</p>
                </a>
            </li>
            @endfor
        </ul>
    </div>
</div>