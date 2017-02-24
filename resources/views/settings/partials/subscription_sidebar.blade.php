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

        <label for="#">Paid Monthly</label>

        <h3 class="settings-profile-heading">$99</h3>

        <label for="#">Max Users</label>

        <h3 class="settings-profile-heading">20</h3>

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