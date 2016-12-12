<header class="search-bar">
    <div class="row">
        @if (\Auth::user()->belongsToAgencyAccount())
            <div class="col-md-3">
                <div class="header-clients">
                    <div class="dropdown-client">
                        <a href="#" class="drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class='account-selector-main'>
                                <span class="company-logo">
                                    <img src="/images/logo-client-fake.jpg" alt="XX">
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
                                            <img src="/images/logo-client-fake.jpg" alt="XX">
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

            <div class="col-md-5">
                @include('elements.searchbar-form-field')
            </div>
        @else
            <div class="col-md-8">
                @include('elements.searchbar-form-field')
            </div>
        @endif

        <div class="col-md-4 text-right">
            <a href="https://contentlaunch.uservoice.com/" class="support-icon icon-question" title="Support" target="_blank"''>
            </a>
            <button class="search-bar-button-primary btn-create">
                Create
                {{-- <span class="caret"></span> --}}
            </button>
            <button class="search-bar-button add-task-action">
                <i class="icon-checklist"></i>
            </button>

            <a href='/logout' class="search-bar-button">
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