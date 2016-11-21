<header class="search-bar">
    <div class="row">
        @if (\Auth::user()->belongsToAgencyAccount())
            <div class="col-md-3">
                <div class="header-clients">
                    <div class="dropdown-client">
                        <a href="#" class="drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="company-logo"><img src="/images/logo-client-fake.jpg" alt="XX"></span>
                            {{ $selectedAccount->name }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($accountsList as $account)
                                <li>
                                    <a href="#">
                                        <span class="company-logo">
                                            <img src="/images/logo-client-fake.jpg" alt="XX">
                                        </span>
                                        {{ $account->name }}
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
                <input type="text" class="search-bar-input" placeholder="Search anything (content, user, rating...)">
            </div>
        @else
            <div class="col-md-8">
                <input type="text" class="search-bar-input" placeholder="Search anything (content, user, rating...)">
            </div>
        @endif

        <div class="col-md-4 text-right">
            <button class="search-bar-button-primary btn-create">
                Create
                <span class="caret"></span>
            </button>
            <button class="search-bar-button add-task-action">
                <i class="icon-checklist"></i>
            </button>
            <!--
            <button class="search-bar-button">
                <i class="icon-users"></i>
            </button>

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