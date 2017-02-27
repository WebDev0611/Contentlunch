@if(App\Account::selectedAccount()->activeSubscriptions()->isEmpty())
<div class="alert alert-info alert-forms freemium-notification alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>You are using free, limited version of the ContentLaunch</h4>

    <p>You can {!! $restriction !!}. Switch to paid account to remove
        this and other limitations.</p>

    <a href="{{route('subscription')}}">
        <button class="btn btn-upgrade">Upgrade now</button>
    </a>
</div>
@endif