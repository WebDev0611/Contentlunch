<div id="addTaskCalendar" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Add task to calendar</h4>
            </div>
            <div class="col-md-6 text-right">
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
            <div class="dropdown">
                <input type="text" class="input" placeholder="Select one or more Team Members" data-toggle="dropdown">
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header-secondary">
                        <span class="dropdown-header-secondary-text">
                            Select team member
                        </span>
                        <button class="button button-micro pull-right text-uppercase">
                            Submit
                        </button>
                    </li>
                    <li>
                        <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                    </li>
                    <li>
                        <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                                <img src="/images/avatar.jpg" alt="#">
                            </span>
                        </label>
                        <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                                <img src="/images/avatar.jpg" alt="#">
                            </span>
                        </label>
                        <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                                <img src="/images/avatar.jpg" alt="#">
                            </span>
                        </label>
                        <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                                <img src="/images/avatar.jpg" alt="#">
                            </span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <label for="fileUpload" class="file-upload-container">
            <input id="fileUpload" type="file" class="file-upload">
            <span>
                <i class="icon-add-content"></i>
                Attach one or more documents
            </span>
        </label>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>

<script type="text/template" id="calendar-dropdown-template">
     <div class="calendar-schedule-dropdown-wrapper" style="display:none">
        <div class="calendar-schedule-dropdown">
            <button type="button" class="button button-action" data-toggle="dropdown">
                <i class="icon-add-circle"></i>
            </button>
             <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header important date-popup-label">Wed, Mar 4, 2016, 01 PM</li>
                <li>
                  <a href="#" data-toggle="modal" data-target="#addIdeaCalendar">Add Idea</a>
                </li>
                <li>
                  <a href="#" data-toggle="modal" data-target="#addContentCalendar">Add Content</a>
                </li>
                <li>
                  <a href="javascript:;" class="tool-add-task">Add Task</a>
                </li>
              </ul>
        </div>
    </div>
</script>
