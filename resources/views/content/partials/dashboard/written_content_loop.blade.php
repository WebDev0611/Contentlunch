<div class="create-panel-table">
    <div class="create-panel-table-cell cell-size-5">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell cell-size-70">
        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->present()->createdAt) }}
        </span>
    </div>
    <div class="create-panel-table-cell text-center cell-size-5">
        <i class="tooltip-icon large icon-arrange-mini" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lorem Ipsum"></i>
    </div>
    <div class="create-panel-table-cell text-right cell-size-15">
        <span class="dashboard-performing-text small">
            DUE: <strong>{{ strtoupper($content->present()->dueDate) }}</strong>
        </span>
    </div>
    <div class="create-panel-table-cell text-right cell-size-5">
        <div class="create-dropdown">
            <button type="button" class="button button-action" data-toggle="dropdown">
                <i class="icon-add-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="javascript:;" onclick="location.href='/edit/{{$content->id}}';">Write It</a>
                </li>
            </ul>
        </div>
    </div>
</div>