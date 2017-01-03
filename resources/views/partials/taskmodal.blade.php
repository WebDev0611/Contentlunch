<div id="addTaskModal" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create Task</h4>
                <div id="task-status-alert" class="alert hidden">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div id="task-status-text"></div>
                </div>
            </div>
            <div class="col-md-6 text-right" id="task-menu">
                <button class="button button-primary button-small text-uppercase" id="add-task-button">Add Task</button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <div class="input-form-group">
            <label for="#">Task Name</label>
            <input type="text" name="task-name" id="task-name" class="input" placeholder="Enter Task Name">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-form-group">
                    <label for="#">Starts</label>
                    <input type="text" name="task-start-date" id="task-start-date" class="input input-calendar" placeholder="Select Date">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-form-group">
                    <label for="#">Due</label>
                    <input type="text" name="task-due-date" id="task-due-date" class="input input-calendar" placeholder="Select Date">
                </div>
            </div>
        </div>
        <div class="input-form-group">
            <label for="#">Task Explanation</label>
            <textarea rows="4" class="input" id="task-explanation" name="task-explanation" placeholder="Short Task Explanation"></textarea>
        </div>
        <div class="input-form-group">
            <label for="#">Reference URL</label>
            <input type="text" class="input" name="task-url" id="task-url" placeholder="Paste URL">
        </div>

        <div class="input-form-group">
            <label for="#">Assign Task To</label>
            <ul class="sidemodal-list-items" id='task-assignment'>
                @foreach (\App\Account::selectedAccount()->users as $user)
                    @php
                        $isChecked = $user->id == Auth::id() ? 'checked="checked"' : '';
                    @endphp
                    <li>
                        <label class="checkbox-primary">
                            <input type="checkbox" data-id='{{ $user->id }}' {{ $isChecked }}>
                            <span>{{ $user->name }}</span>
                        </label>
                    </li>
                @endforeach
            <ul>
        </div>

        @if (@$content)
        <div class="input-form-group">
            <label class='checkbox-primary'>
                <input type="checkbox" data-id='{{ $content->id }}' checked='checked' name='is_content_task' id='is_content_task'>
                <span>Assign this Task to this piece of content</span>
            </label>
        </div>
        @endif

        <div class="input-form-group">
            <label>Attach one or more documents</label>
            <div class="dropzone" id='task-attachment-uploader'>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>