<nav class="navigation">
    <a href="#" class="navigation-logo">
        <img src="/2016/images/logo.svg" alt="Content Launch">
    </a>
    <ul class="navigation-menu">
        <li>
            <a href="/2016/home" class="navigation-menu-profile ">
                <img src="/2016/images/avatar.jpg" alt="#">
            </a>
        </li>
        <li>
            <a href="/2016/home" class="navigation-menu-link {{ ( Request::segment(2) == 'home' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-chart">
                    <span>Dashboard</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/2016/plan" class="navigation-menu-link {{ ( Request::segment(2) == 'plan' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-content">
                    <span>PLAN</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/2016/content" class="navigation-menu-link {{ ( Request::segment(2) == 'content' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-dashboard">
                    <span>CREATE</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/2016/calendar" class="navigation-menu-link {{ ( Request::segment(2) == 'calendar' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-calendar">
                    <span>CALENDAR</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/2016/collaborate" class="navigation-menu-link {{ ( Request::segment(2) == 'collaborate' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-users">
                    <span>Collaborate</span>
                </i>
            </a>
        </li>
    </ul>
    <a href="/2016/settings" class="navigation-settings {{ ( Request::segment(2) == 'settings' ) ? 'active': ''  }}">
        <i class="navigation-menu-icon icon-cog"></i>
    </a>
</nav>
