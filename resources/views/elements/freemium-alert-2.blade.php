<div class="alert alert-info alert-forms freemium-notification-2 alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    @if(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'trial')

        <div class="col-md-8 height-60">
            <h4>You are using the 2 week free trial of ContentLaunch</h4>

            <p>You can {!! $restriction !!} during this period. Switch to a paid account to remove
                this and other limitations.</p>
        </div>

    @elseif(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'free')

        <div class="col-md-8 height-60">
            <h4>You are using the free version of ContentLaunch</h4>

            <p>You can {!! $restriction !!}. Switch to a paid account to remove
                this and other limitations.</p>
        </div>

    @endif

    <div class="col-md-4 height-60">
        <a href="{{route('subscription')}}">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>

    <div class="clearfix"></div>
</div>