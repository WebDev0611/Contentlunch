<div class="create-panel-table border-left">
    <div class="create-panel-table-cell">
        @include('content.partials.avatar')
    </div>
    <div class="create-panel-table-cell">
        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-members-text small">
            {{ strtoupper($content->created_at->diffForHumans()) }}
        </span>
    </div>
    <div class="create-panel-table-cell text-right">
        <a href="{{ route('editContent', $content->id) }}"
           class="tooltip-icon large icon-arrange-mini"
           data-toggle="tooltip"
           data-placement="top"
           title=""
           data-original-title="Lorem Ipsum">
        </a>
    </div>
    <div class="create-panel-table-cell text-right">
        <span class="dashboard-performing-text small">
            UPDATED: <strong>{{ $content->updated_at->format('m/d/Y') }}</strong>
        </span>
    </div>
    <div class="create-panel-table-cell text-right">
        <i  class="create-panel-spaceship icon-spaceship-circle open-launch-menu"
            data-content="{{ $content->id }}"
            data-toggle="modal"
            data-target="#launch">
        </i>
    </div>
</div>
