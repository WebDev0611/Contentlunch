<li class="right clearfix">
    <span class="comment-img pull-right">
        <img src="/images/cl-avatar2.png" alt="User Avatar" class="img-circle"/>
    </span>
    <div class="comment-body clearfix">
        <div class="header">
            <strong class="primary-font">{{ $comment->editor->name }}</strong>
            <small class="pull-right text-muted">
                <span class="glyphicon glyphicon-time"></span>{{ Carbon\Carbon::parse($comment->timestamp)->diffForHumans() }}
            </small>
        </div>
        <p>
            {{ $comment->editor->note }}
        </p>
    </div>
</li>