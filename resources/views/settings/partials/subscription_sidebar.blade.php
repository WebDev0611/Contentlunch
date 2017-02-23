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

        <span class="settings-profile-subscription">Paid Subscription</span>

        <label for="#">Paid Monthly</label>
        <h3 class="settings-profile-heading">$700</h3>

        <label for="#">Max Users</label>
        <h3 class="settings-profile-heading">$700</h3>

        <div class="form-group">
            <a href="#" class="text-blue text-uppercase">
                Upgrade Subscription
            </a>
        </div>
        <div class="form-group">
            <label for="#">Payment Info</label>
            <span>
                VISA X-1203
                <a href="#" class="text-blue text-uppercase">
                    <i class="icon-edit"></i>
                    Edit
                </a>
            </span>
        </div>


    </div>
</aside>