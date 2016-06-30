            <div class="calender-menu-buttons-group">
                <button class="calendar-menu-buttons" data-toggle="modal" data-target="#createCalendarModal">
                    <i class="icon-calendar-add"></i>
                </button>
                <div class="calendar-menu-dropdown">
                    <button class="calendar-menu-buttons" data-toggle="dropdown">
                        <i class="icon-calendar-view"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="#">Invite Collaborators</a>
                        </li>
                        <li>
                            <a href="#">Invite Guests</a>
                        </li>
                        <li>
                            <a href="#">Manage Access</a>
                        </li>
                    </ul>
                </div>
                <button class="calendar-menu-buttons" data-toggle="modal" data-target="#filterModal">
                    <i class="icon-cone"></i>
                </button>
                <div class="calendar-menu-dropdown">
                    <button class="calendar-menu-buttons" data-toggle="dropdown">
                        <i class="icon-reverse-direction"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header">Import</li>
                        <li>
                            <a href="#">From iCal</a>
                        </li>
                        <li>
                            <a href="#">From Google Calendar</a>
                        </li>
                        <li class="dropdown-header">Export</li>
                        <li>
                            <a href="#">To Google Calendar</a>
                        </li>
                        <li>
                            <a href="#">To iCal</a>
                        </li>
                        <li>
                            <a href="#">To PDF</a>
                        </li>
                        <li>
                            <a href="#">To Excel</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="calendar-menu-switch">
                <button type="button" class="button button-secondary button-small" onclick="window.location='/2016/daily/<?= date('Y/m/d');?>';">Today</button>
                <div class="btn-group">
                    <button type="button" class="button button-switches button-small ">Month</button>
                    <button type="button" class="button button-switches button-small" onclick="window.location='/2016/weekly';">Week</button>
                    <button type="button" class="button button-switches button-small" onclick="window.location='/2016/daily';">Day</button>
                </div>
            </div>