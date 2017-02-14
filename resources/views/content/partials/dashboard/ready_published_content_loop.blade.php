<div class="create-panel-table border-left">
    <div class="create-panel-table-cell cell-size-5">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell cell-size-65">
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
    <div class="create-panel-table-cell cell-size-15 text-right">
        <span class="dashboard-performing-text small @if ($content->isDueDateCritical()) critical @endif">
            DUE: <strong>{{ strtoupper($content->present()->dueDateFormat) }}</strong>
        </span>
    </div>
    <div class="create-panel-table-cell cell-size-15 text-right">
        <i  class="create-panel-spaceship icon-spaceship-circle open-launch-menu"
            data-content="{{ $content->id }}"
            data-toggle="modal"
            data-target="#launch">
        </i>
    </div>
</div>
