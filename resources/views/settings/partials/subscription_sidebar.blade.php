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

        <span class="settings-profile-subscription paid">Paid Subscription</span>

        <span class="settings-profile-subscription free">FREE Version</span>

        @if($account->activeSubscriptions())

            <label for="#">Paid Monthly</label>

            <h3 class="settings-profile-heading">${{$account->activeSubscriptions()[0]->subscriptionType->price_month}}</h3>

            <label for="#">Start Date</label>

            <h3 class="settings-profile-heading">{{$account->activeSubscriptions()[0]->start_date}}</h3>

            <label for="#">Expiration Date</label>

            <h3 class="settings-profile-heading">{{$account->activeSubscriptions()[0]->expiration_date}}</h3>

            <label for="#">Max Users</label>

            <h3 class="settings-profile-heading">2</h3>

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