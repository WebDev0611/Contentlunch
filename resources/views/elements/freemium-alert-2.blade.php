@can('guests-denied')
@if(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'trial')

<div class="alert alert-info alert-forms freemium-notification-2 alert-dismissable">
    {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <div class="col-md-8 height-60">
            <h4>You are using the 2 week free trial of {{ trans('messages.company') }}</h4>

            <p>You can {!! $restriction !!} during this period. Switch to a paid account to remove
                this and other limitations.</p>
        </div>

    <div class="col-md-4 height-60">
        <a href="{{route('subscription')}}">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>

    <div class="clearfix"></div>
</div>

@elseif(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'free')

    <div class="alert alert-info alert-forms freemium-notification-2 alert-dismissable">
        {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <div class="col-md-8 height-60">
            <h4>You are using the free version of {{ trans('messages.company') }}</h4>

            <p>You can {!! $restriction !!}. Switch to a paid account to remove
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
@endcan