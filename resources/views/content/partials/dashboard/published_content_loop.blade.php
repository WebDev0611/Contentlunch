<div class="create-panel-table">
    <div class="create-panel-table-cell cell-size-5">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell cell-size-75">
        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->created_at->diffForHumans()) }}
        </span>
    </div>
    <div class="create-panel-table-cell text-center cell-size-5">
        <i class="tooltip-icon large icon-arrange-mini" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lorem Ipsum"></i>
    </div>
    <div class="create-panel-table-cell text-right cell-size-15">
        <span class="dashboard-performing-text small">
            UPDATED: <strong>{{ strtoupper($content->updated_at->diffForHumans()) }}</strong>
        </span>
    </div>
</div>