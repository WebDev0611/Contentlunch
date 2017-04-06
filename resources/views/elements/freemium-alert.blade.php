@php
$restriction = isset($restriction) ? $restriction : 'try out all features of the application, but some functionalities are limited';
@endphp

@if(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'trial')

<div class="alert alert-info alert-forms freemium-notification alert-dismissable">
    {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <h4>You are using the 2 weeks free trial of ContentLaunch</h4>

        <p>You can {!! $restriction !!} during this period. Subscribe to a paid account to remove
            current limitations.</p>

    <a href="{{route('subscription')}}">
        <button class="btn btn-upgrade">Upgrade now</button>
    </a>
</div>

@elseif(App\Account::selectedAccount()->activeSubscriptions()->first()->subscriptionType->slug == 'free')

    <div class="alert alert-info alert-forms freemium-notification alert-dismissable">
        {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}

        <h4>You are using the free version of ContentLaunch</h4>

        <p>You can {!! $restriction !!}. Switch to a paid account to remove
            current limitations.</p>

        <a href="{{route('subscription')}}">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>

@endif