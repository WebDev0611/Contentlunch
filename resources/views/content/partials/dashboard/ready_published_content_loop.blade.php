<div class="create-panel-table border-left">
    <div class="create-panel-table-cell cell-size-5">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell cell-size-75">
        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->present()->createdAt) }}
        </span>
    </div>
    <div class="create-panel-table-cell cell-size-5 text-center">
        <a href="{{ route('editContent', $content->id) }}"
           class="tooltip-icon large icon-arrange-mini"
           data-toggle="tooltip"
           data-placement="top"
           title=""
           data-original-title="Lorem Ipsum">
        </a>
    </div>
    <div class="create-panel-table-cell cell-size-15 text-right">
        <span class="dashboard-performing-text small">
            DUE: <strong>{{ strtoupper($content->present()->dueDate) }}</strong>
        </span>
    </div>
    <div class="create-panel-table-cell cell-size-5 text-right">
        <i  class="create-panel-spaceship icon-spaceship-circle open-launch-menu"
            data-content="{{ $content->id }}"
            data-toggle="modal"
            data-target="#launch">
        </i>
    </div>
</div>
