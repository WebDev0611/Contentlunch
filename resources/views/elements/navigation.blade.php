<nav class="navigation">
    <a href="/" class="navigation-logo">
        <img src="/images/logo.svg" alt="Content Launch">
    </a>
    <ul class="navigation-menu">
        @can('guests-denied')
        <li>
            <a href="{{route('subscription')}}" class="navigation-menu-profile navigation-menu-link {{ Request::segment(1) != 'subscription' ?: 'active' }}">

                @if (\Auth::user()->belongsToAgencyAccount())
                    <img src="{{ App\Account::selectedAccount()->present()->account_image }}" alt="{{ App\Account::selectedAccount()->name }}">
                @else
                    <img src="{{ \Auth::user()->present()->profile_image }}" alt="">
                @endif

                @if(App\Account::selectedAccount()->activePaidSubscriptions()->isEmpty())
                    <div class="app-type">
                        <p class="app-agency">Subscribe</p>
                    </div>
                @endif
            </a>
        </li>
        @endcan

        @can('guests-denied')
        @if (\Auth::user()->belongsToAgencyAccount())
        <li>
            <a href="/agencies" class="navigation-menu-link {{ Request::segment(1) != 'agencies' ?: 'active' }}">
                <i class="navigation-menu-icon icon-navigation-agency">
                    <span>Agency</span>
                </i>
            </a>
        </li>
        @endif
        @endcan

        <li>
            <a href="/home" class="navigation-menu-link {{ Request::segment(1) != 'home' ?: 'active' }}">
                <i class="navigation-menu-icon icon-navigation-chart">
                    <span>Dashboard</span>
                </i>
            </a>
        </li>

        @can('guests-denied')
        <li>
            <a href="/plan" class="navigation-menu-link {{ Request::segment(1) != 'plan' ?: 'active' }}">
                <i class="navigation-menu-icon icon-navigation-content">
                    <span>PLAN</span>
                </i>
            </a>
        </li>
        @endcan

        <li>
            <a href="/content" class="navigation-menu-link {{ Request::segment(1) != 'content' ?: 'active' }}">
                <i class="navigation-menu-icon icon-navigation-dashboard">
                    <span>CREATE</span>
                </i>
            </a>
        </li>

        @can('guests-denied')
        <li>
            <a href="/calendar" class="navigation-menu-link {{ Request::segment(1) != 'calendar' ?: 'active' }}">
                <i class="navigation-menu-icon icon-navigation-calendar">
                    <span>CALENDAR</span>
                </i>
            </a>
        </li>
        @endcan

        @can('guests-denied')
        <li>
            <a href="/collaborate" class="navigation-menu-link {{ Request::segment(1) == 'collaborate' ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-users">
                    <span>Collaborate</span>
                </i>
            </a>
        </li>
        @endcan

    </ul>
    <a href="/settings" class="navigation-settings {{ Request::segment(2) == 'settings' ? 'active': ''  }}">
        <i class="navigation-menu-icon icon-cog"></i>
    </a>
</nav>
