<nav class="navigation">
    <a href="#" class="navigation-logo">
        <img src="/images/logo.svg" alt="Content Launch">
    </a>
    <ul class="navigation-menu">
        <li>
            <a href="javascript:;" class="navigation-menu-profile">
                <img src="/images/cl-avatar2.png" alt="#">
                <div class="app-type">
                  <p class="app-agency">Agency</p>
                </div>
            </a>
        </li>
        <li>
            <a href="/agency" class="navigation-menu-link {{ ( Request::segment(1) == 'agency' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-agency">
                    <span>Agency</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/home" class="navigation-menu-link {{ ( Request::segment(1) == 'home' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-chart">
                    <span>Dashboard</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/plan" class="navigation-menu-link {{ ( Request::segment(1) == 'plan' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-content">
                    <span>PLAN</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/content" class="navigation-menu-link {{ ( Request::segment(1) == 'content' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-dashboard">
                    <span>CREATE</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/calendar" class="navigation-menu-link {{ ( Request::segment(1) == 'calendar' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-calendar">
                    <span>CALENDAR</span>
                </i>
            </a>
        </li>
        <li>
            <a href="/collaborate" class="navigation-menu-link {{ ( Request::segment(1) == 'collaborate' ) ? 'active': ''  }}">
                <i class="navigation-menu-icon icon-navigation-users">
                    <span>Collaborate</span>
                </i>
            </a>
        </li>
    </ul>
    <a href="/settings" class="navigation-settings {{ ( Request::segment(1) == 'settings' ) ? 'active': ''  }}">
        <i class="navigation-menu-icon icon-cog"></i>
    </a>
</nav>
