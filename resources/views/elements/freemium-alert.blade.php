<div class="alert alert-info alert-forms freemium-notification alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    @if(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'trial')

        <h4>You are using the 2 week free trial of ContentLaunch</h4>

        <p>You can {!! $restriction !!} during this period. Switch to a paid account to remove
            this and other limitations.</p>

    @elseif(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'free')

        <h4>You are using the free version of ContentLaunch</h4>

        <p>You can {!! $restriction !!}. Switch to a paid account to remove
            this and other limitations.</p>

    @endif

    <a href="{{route('subscription')}}">
        <button class="btn btn-upgrade">Upgrade now</button>
    </a>
</div>