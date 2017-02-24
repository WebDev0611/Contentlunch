<li class="right clearfix">
    <span class="comment-img pull-right">
        <img src="{{$writer->photo}}" alt="User Avatar" class="img-circle"/>
    </span>
    <div class="comment-body clearfix">
        <div class="header">
            <strong class="primary-font">{{ $writer->name }}</strong>
            <small class="pull-right text-muted">
                <span class="glyphicon glyphicon-time"></span>{{ Carbon\Carbon::parse($comment->timestamp)->diffForHumans() }}
            </small>
        </div>
        <p>
            {{ $comment->writer->note }}
        </p>
    </div>
</li>