<div class="panel-header">
    <ul class="panel-tabs withborder">
        {{--
        <li class="active">
            <a href="#sidetab-tasks" role="tab" data-toggle="tab">Content Tasks</a>
        </li>
        <li>
            <a href="#sidetab-activity" role="tab" data-toggle="tab">Activity</a>
        </li>
        --}}

        <li class='active'>
            <a href="#sidetab-history" role="tab" data-toggle="tab">History</a>
        </li>
    </ul>
</div>


<div class="tab-content">
    <!-- Content Task Panel -->
    <div role="tabpanel" class="sidepanel tab-pane" id="sidetab-tasks">
        @include('content.partials.editor.sidebar_tasks')
    </div> <!-- End Content Task Panel -->

    <!-- Tab 2: Activity -->
    <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-activity">
        @include('content.partials.editor.sidebar_activity')
    </div>

    <!-- Tab 3: History -->
    <div class="sidepanel tab-pane active" role="tabpanel" id="sidetab-history">
        @include('content.partials.editor.sidebar_history')
    </div>
</div>


<!-- Collaborators / Guests tabs -->
<div class="sidepanel-head">
    <ul class="panel-tabs withborder withtopborder">
        <li class="active">
            <a href="#sidetab-collaborators" data-toggle="tab">Collaborators</a>
        </li>
        {{--
        <li>
            <a href="#sidetab-guests" data-toggle="tab">Guests</a>
        </li>
        <li class="tablink">
            <a href="#" class="btn button-text"><i class="icon-add-person"></i></a>
        </li>
        --}}
    </ul>
</div>


<div class="tab-content hide">
    <!-- Tab 1: Collaborators -->
    <div class="sidepanel nopadding tab-pane active" id="sidetab-collaborators">
        @include('content.partials.editor.sidebar_collaborators')
    </div>

    <!-- Tab 2: Guests -->
    <div class="sidepanel nopadding tab-pane" id="sidetab-guests">
        @include('content.partials.editor.sidebar_guests')
    </div>
</div>