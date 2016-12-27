<div class="create-panel-table">
    <div class="create-panel-table-cell">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell create-panel-table-cell-large">
        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->created_at->diffForHumans()) }}
        </span>
    </div>
    <div class="create-panel-table-cell text-right">
        <i class="tooltip-icon large icon-arrange-mini" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lorem Ipsum"></i>
    </div>
    <div class="create-panel-table-cell text-right">
        <span class="dashboard-performing-text small">
            UPDATED: <strong>{{ $content->updated_at->format('m/d/Y') }}</strong>
        </span>
    </div>
    <div class="create-panel-table-cell text-right">
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