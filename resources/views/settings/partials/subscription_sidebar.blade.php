<aside class="panel-sidebar right-separator">
    <div class="panel-container text-center">
        <div class="settings-profile-image">
            <img src="{{ $user->present()->profile_image }}" alt="#">
        </div>
        <div class="settings-profile-info">
            <h4>{{ $user->name }}</h4>
            <span>
                {{ $user->present()->location }}
            </span>
        </div>

        @if($activeSubscription->isPaid())

            <span class="settings-profile-subscription paid">Paid Subscription</span>

            <label for="#">{{$activeSubscription->subscriptionType->name}}</label>
            <h3 class="settings-profile-heading">${{number_format($activeSubscription->subscriptionType->price)}}</h3>

            <label for="#">Start Date</label>
            <h3 class="settings-profile-heading">{{date_format(date_create($activeSubscription->start_date), "n-j-y") }}</h3>

            <label for="#">Expiration Date</label>
            <h3 class="settings-profile-heading">{{date_format(date_create($activeSubscription->expiration_date), "n-j-y") }}</h3>

            <label for="#">Users</label>
            <h3 class="settings-profile-heading">{{count($account->users)}}/{{$account->limit('users_per_account')}}</h3>

            @if($account->isAgencyAccount() || $account->isSubAccount())

                <label for="#">Clients</label>
                <h3 class="settings-profile-heading">{{count($user->agencyAccount()->childAccounts)}}</h3>

            @endif

        @else

            <span class="settings-profile-subscription free">FREE Version</span>

            <label for="#">Paid Monthly</label>
            <h3 class="settings-profile-heading">$0.00</h3>

            <label for="#">Max Users</label>
            <h3 class="settings-profile-heading">{{ $account->limit('users_per_account') }}</h3>

            @if($account->isAgencyAccount () || $account->isSubAccount())

                <label for="#">Clients</label>
                <h3 class="settings-profile-heading">{{ count($user->agencyAccount()->childAccounts) }}/{{ $account->limit('subaccounts_per_account') }}</h3>

            @endif

        @endif

        @if(isset($userCard))
            <div class="form-group">
                <label for="#">Payment Info</label>
            <span>
                {{$userCard->brand}} XXX-{{$userCard->last4}}
                <a href="#" class="text-blue text-uppercase" id="edit-payment">
                    <i class="icon-edit"></i>
                    Edit
                </a>
            </span>
            </div>
        @endif

    </div>
</aside>