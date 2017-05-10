<aside class="panel-sidebar right-separator">
    <div class="panel-container text-center">
        <div class="settings-profile-image">
            <img src="{{ Auth::user()->present()->profile_image }}" alt="#">
        </div>
        <div class="settings-profile-info">
            <h4>{{ Auth::user()->name }}</h4>
            <span>
                {{ Auth::user()->present()->location }}
            </span>
        </div>

        @can("guests-denied")
            @if ($activeSubscription->isPaid())
                <span class="settings-profile-subscription paid">Paid Subscription</span>

                <label for="#">{{ $activeSubscription->subscriptionType->name }}</label>
                <h3 class="settings-profile-heading">{{ $activeSubscription->present()->price }}</h3>

                <label for="#">Start Date</label>
                <h3 class="settings-profile-heading">{{ $activeSubscription->present()->startDateFormat }}</h3>

                <label for="#">Expiration Date</label>
                <h3 class="settings-profile-heading">{{ $activeSubscription->present()->expirationDateFormat  }}</h3>

                <label for="#">Users</label>
                <h3 class="settings-profile-heading">{{ $account->present()->usersCountStatus }}</h3>

                @if ($account->isAgencyAccount() || $account->isSubAccount())

                    <label for="#">Clients</label>
                    <h3 class="settings-profile-heading">{{count($user->agencyAccount()->activeChildAccounts)}}</h3>

                @endif
            @else

                <span class="settings-profile-subscription free">FREE Version</span>

                <label for="#">Paid Monthly</label>
                <h3 class="settings-profile-heading">$0.00</h3>

                <label for="#">Max Users</label>
                <h3 class="settings-profile-heading">{{ $account->limit('users_per_account') }}</h3>

                @if ($account->isAgencyAccount () || $account->isSubAccount())
                    <label for="#">Clients</label>
                    <h3 class="settings-profile-heading">
                        {{ $account->present()->subAccountsStatus }}
                    </h3>
                @endif
            @endif
        @else
            <span class="settings-profile-subscription free">Guest Account</span>
        @endcan

        @can('guests-denied')
        <div class="form-group">
            <label for="#">Payment Info</label>
            <span>
                @if (isset($userCard))
                    {{ $userCard->brand }} XXX-{{ $userCard->last4 }}
                    <a href="#" class="text-blue text-uppercase" id="edit-payment">
                        <i class="icon-edit"></i>
                        Edit
                    </a>
                @else
                    <p class="no-card">No Credit Card configured</p>
                @endif
            </span>
        </div>
        @endcan

    </div>
</aside>