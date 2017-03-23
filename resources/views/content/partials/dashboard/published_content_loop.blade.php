<div class="create-panel-table">
    <div class="create-panel-table-cell cell-size-5">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell cell-size-75">
        <h5 class="dashboard-tasks-title">
            <a href="{{ route('editContent', $content) }}">{{ $content->present()->title }}</a>
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->present()->createdAt) }}
        </span>
    </div>
    <div class="create-panel-table-cell text-center cell-size-5"
        data-toggle="tooltip"
        data-placement="top"
        title="{{ $content->present()->contentType }}"
        data-original-title="{{ $content->present()->contentType }}">

        <i class="tooltip-icon large {{ $content->present()->contentIcon }}"></i>
    </div>
    <div class="create-panel-table-cell text-right cell-size-15">
        <span class="dashboard-performing-text small">
            UPDATED: <strong>{{ strtoupper($content->present()->updatedAtFormat) }}</strong>
        </span>
    </div>
</div>