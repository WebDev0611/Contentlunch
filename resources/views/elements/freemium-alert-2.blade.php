@if(App\Account::selectedAccount()->activeSubscriptions()->isEmpty())
<div class="alert alert-info alert-forms freemium-notification-2 alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    <div class="col-md-8 height-60">
        <h4>You are using free, limited version of the ContentLaunch</h4>
        <p>You can {!! $restriction !!}. Upgrade to paid account to remove
            this and other limitations.</p>
    </div>

    <div class="col-md-4 height-60">
        <a href="{{route('subscription')}}">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>

    <div class="clearfix"></div>
</div>
@endif