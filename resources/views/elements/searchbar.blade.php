<header class="search-bar">
    <div class="row">
        @if (\Auth::user()->belongsToAgencyAccount() && !Auth::user()->isGuest())
            <div class="col-md-3">
                <div class="header-clients">
                    <div class="dropdown-client">
                        <a href="#" class="drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class='account-selector-main'>
                                <span class="company-logo">
                                    <img src="{{ $selectedAccount->present()->account_image }}" alt="{{ $selectedAccount->name }}">
                                </span>
                                <span>{{ $selectedAccount->name }}</span>
                            </span>
                            <span class="caret"></span>
                        </a>
                        <ul class="account-selector-list dropdown-menu">
                            @foreach ($accountsList as $account)
                                <li>
                                    <a href="#" data-account-id="{{ $account->id }}" class='account-selector'>
                                        <span class="company-logo">
                                            <img src="{{ $account->present()->account_image }}" alt="{{ $account->name }}">
                                        </span>
                                        <span>{{ $account->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="#" class="btn-addclient"
                            data-toggle="modal"
                            data-target="#create-subaccount">

                            <i class="icon-add"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @include('elements.searchbar-form-field')
            </div>
        @else
            <div class="col-md-7">
                @include('elements.searchbar-form-field')
            </div>
        @endif

        <div class="col-md-5 text-right">

            <a href="https://contentlaunch.uservoice.com" class='support-text' title='Click here for support' target='_blank'>
                Need help?
                <i class="fa fa-question-circle"></i>
            </a>

            @if(App\Account::selectedAccount()->activePaidSubscriptions()->isEmpty())
                <a href="{{route('subscription')}}">
                    <button class="btn btn-warning">
                        Upgrade
                    </button>
                </a>
            @endif

            @can('guests-denied')
            <button class="search-bar-button-primary btn-create">
                Create
            </button>
            <button class="search-bar-button add-task-action" title="Create a Task">
                <i class="icon-checklist"></i>
            </button>
            @endcan

            <open-message-bar-button></open-message-bar-button>

            <a href='/logout' class="logout-button search-bar-button">
                Logout
            </a>
            <!--

            <button class="search-bar-button">
                <i class="icon-chat"></i>
            </button>
            -->
        </div>
    </div>
</header>

@if (\Auth::user()->belongsToAgencyAccount())
    @include('agency.partials.create_subaccount_modal')
@endif