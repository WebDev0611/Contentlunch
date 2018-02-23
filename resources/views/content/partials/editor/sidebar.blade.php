<div class="panel-header">
    <ul class="panel-tabs withborder">
        <li class="active">
            <a href="#sidetab-communication" data-toggle='tab'>Communication</a>
        </li>

        <li>
            <a href="#sidetab-tasks" role='tab' data-toggle='tab'>Content Tasks</a>
        </li>

        <li>
            <a href="#sidetab-history" role="tab" data-toggle="tab">History</a>
        </li>
    </ul>
</div>

<div class="tab-content">
    <div class="sidepanel tab-pane nopadding active" role="tabpanel" id="sidetab-communication">
        <content-messages
            content-id="{{ $content->id }}"
            has-access='{!! $content->hasAccessToMessages(Auth::user()); !!}'>
        </content-messages>
    </div>

    <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-tasks">
        @include('content.partials.editor.sidebar_tasks')
    </div>

    <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-activity">
        @include('content.partials.editor.sidebar_activity')
    </div>

    <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-history">
        @include('content.partials.editor.sidebar_history')
    </div>
</div>

<div class="sidepanel-head">
    <ul class="panel-tabs withborder withtopborder">
        <li class='active'>
            <a href="#sidetab-collaborators" class='add-person-tab' data-toggle="tab">Collaborators</a>
        </li>

        <li>
            <a href="#sidetab-guests" class='add-person-tab' data-toggle="tab">Clients</a>
        </li>

        @if ($isCollaborator)
        <li class="tablink">
            <a href="#" id='add-person-to-content' class="btn button-text"><i class="icon-add-person"></i></a>
        </li>
        @endif
    </ul>
</div>


<div class="tab-content">
    <div class="sidepanel nopadding tab-pane active" id="sidetab-collaborators">
        @include('content.partials.editor.sidebar_collaborators')
    </div>

    <div class="sidepanel nopadding tab-pane" id="sidetab-guests">
        <guests-list content-id='{{ $content->id }}' type='content'></guests-list>
    </div>
</div>