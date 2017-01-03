<div class="content-tasks-box-container">

    <div class="twocols">
        <p class="intro">Tasks to be completed on this content piece</p>
        <a href="#newtask" class="btn button-text withendicon add-task-action" data-content-id="{{ $content->id }}">
            NEW TASK<i class="icon-add"></i>
        </a>
    </div>

    <!-- New Task -->
    <div class="task new collapse" id="newtask">

        <p class="title">New task</p>

        <div class="task-content">

            <div class="input-form-group">
                <label for="#">Task name</label>
                <input type="text" class="input" placeholder="Enter name">
            </div>


            <div class="form-group">
                <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                    </ul>
                    <div class="dropdown pull-right">
                        <button type="button" class="button button-action large"
                                data-toggle="dropdown">
                            <i class="icon-add-circle"></i>
                        </button>
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
                                <input type="text" class="dropdown-header-secondary-search"
                                       placeholder="Team Member Name">
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
                </fieldset>
            </div>

            <div class="input-form-group">
                <label for="#">Deadline</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                </div>
            </div>

            <div class="input-form-group">
                <label for="#">Completed by</label>
                <select name="" class="input">
                    <option disabled selected>Please select</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                </select>
            </div>

            <div class="button-bar">
                <button type="button" class="button button-small button-outline-secondary">
                    CANCEL
                </button>
                <button type="button" class="button button-small">SAVE</button>
            </div>

        </div>

    </div> <!-- End New Task -->

    <!-- Task -->
    @foreach ($tasks as $task)
    <div class="task">
        <div class="body">
            <div class="checkcircle">
                <i class="icon-check-light"></i>
            </div>

            <div class="user-avatar">
                <img src="{{ $task->user->present()->profile_image }}" alt="{{ $task->user->name }}" title="{{ $task->user->name }}">
            </div>

            <p class="title">{{ $task->name }}</p>
            <p>{{ $task->present()->dueDate }}</p>
        </div>

        <div class="task-actions">
            <ul class="list-inline list-actions">
                <li><a href="#task-{{ $task->id }}" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                <li><a href="#"><i class="icon-trash"></i></a></li>
            </ul>
        </div>

        <div class="task-content collapse" id="task-{{ $task->id }}">
            <div class="form-group">
                <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                        <li>
                            <img src="/images/avatar.jpg" alt="#">
                        </li>
                    </ul>
                    <div class="dropdown pull-right">
                        <button type="button" class="button button-action large"
                                data-toggle="dropdown">
                            <i class="icon-add-circle"></i>
                        </button>
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
                                <input type="text" class="dropdown-header-secondary-search"
                                       placeholder="Team Member Name">
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
                </fieldset>
            </div>

            <div class="input-form-group">
                <label for="#">Deadline</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                </div>
            </div>

            <div class="button-bar">
                <button type="button" class="button button-small button-outline-secondary">
                    CANCEL
                </button>
                <button type="button" class="button button-small">SAVE</button>
            </div>
        </div>
    </div>
    @endforeach
</div>