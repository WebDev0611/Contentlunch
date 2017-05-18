@can('guests-denied')
@php
$restriction = isset($restriction) ? $restriction : 'try out all features of the app, but some functionality is limited';
@endphp

@if(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'trial')

<div class="alert alert-info alert-forms freemium-notification alert-dismissable">
    {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <h4>You are using the 2 week free trial of ContentLaunch</h4>

        <p>You can {!! $restriction !!} during this period.
            Switch to a paid account to remove this and other limitations.</p>

    <a href="{{route('subscription')}}">
        <button class="btn btn-upgrade">Upgrade now</button>
    </a>
</div>

@elseif(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'free')

    <div class="alert alert-info alert-forms freemium-notification alert-dismissable">
        {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <h4>You are using the free version of ContentLaunch</h4>

        <p>You can {!! $restriction !!}. Switch to a paid account to remove
            this and other limitations.</p>

        <a href="{{route('subscription')}}">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>

@endif
@endcan