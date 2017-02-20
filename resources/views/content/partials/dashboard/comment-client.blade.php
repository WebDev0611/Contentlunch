<li class="left clearfix">
    <span class="comment-img pull-left">
        <img src="{{ \Auth::user()->present()->profile_image }}" alt="User Avatar" class="img-circle"/>
    </span>
    <div class="comment-body clearfix">
        <div class="header">
            <strong class="primary-font">{{ \Auth::user()->present()->name }}</strong>
            <small class="pull-right text-muted">
                <span class="glyphicon glyphicon-time"></span>{{ Carbon\Carbon::parse($comment->timestamp)->diffForHumans() }}
            </small>
        </div>
        <p>
            {{ $comment->client->note }}
        </p>
    </div>
</li>