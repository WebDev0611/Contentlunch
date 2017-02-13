<div id="createCalendarModal" class="sidemodal">
    <div class="sidemodal-header">
        <h4 class="sidemodal-header-title">Create new calendar</h4>
        <button class="sidemodal-close" data-dismiss="modal">
            <i class="icon-remove"></i>
        </button>
    </div>
    <div class="sidemodal-container">
        <div class="form-group">
            <label for="#">Enter Calendar Name</label>
            <input type="text" class="input" id="calendar_name" placeholder="Enter Calendar Name">
        </div>
        <div class="form-group">
            <label for="#">VISIBLE CONTENT TYPES</label>
            <ul class="sidemodal-list-items">
                <li>
                    <label for="show_tasks" class="checkbox-primary">
                        <input id="show_tasks" type="checkbox" value="1" checked>
                        <span>Tasks</span>
                    </label>
                </li>
                <li>
                    <label for="show_ideas" class="checkbox-primary">
                        <input id="show_ideas" type="checkbox" value="1" checked>
                        <span>Ideas</span>
                    </label>
                </li>
                @foreach($available_content_types as $content_type)
                    <li>
                        <label for="content_type_{{$content_type->id}}" class="checkbox-primary checkbox-content-types">
                            <input id="content_type_{{$content_type->id}}" type="checkbox" value="{{$content_type->id}}" checked>
                            <span>{{$content_type->name}}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="form-group">
            <label for="#">Calendar Color</label>
            <label for="CalendarColor" class="checkbox-color CL-f3ee8b">
                <input id="CalendarColor" type="checkbox" value="f3ee8b">
                <span></span>
            </label>
            <label for="CalendarColor1" class="checkbox-color CL-fdcc5b">
                <input id="CalendarColor1" type="checkbox" value="fdcc5b">
                <span></span>
            </label>
            <label for="CalendarColor2" class="checkbox-color CL-edab7e">
                <input id="CalendarColor2" type="checkbox" value="edab7e">
                <span></span>
            </label>
            <label for="CalendarColor3" class="checkbox-color CL-d68ae6">
                <input id="CalendarColor3" type="checkbox" value="d68ae6">
                <span></span>
            </label>
            <label for="CalendarColor4" class="checkbox-color CL-a089e7">
                <input id="CalendarColor4" type="checkbox" value="a089e7">
                <span></span>
            </label>
            <label for="CalendarColor5" class="checkbox-color CL-5496cb" >
                <input id="CalendarColor5" type="checkbox" value="5496cb">
                <span></span>
            </label>
            <label for="CalendarColor6" class="checkbox-color CL-7cd1d1">
                <input id="CalendarColor6" type="checkbox" value="7cd1d1">
                <span></span>
            </label>
        </div>


        <!--
        <fieldset class="form-fieldset">
            <legend class="form-legend">Invite Guests</legend>
            <div class="form-group">
                <input type="text" class="input input-secondary" placeholder="Enter one of more addresses">
            </div>
            <div class="form-group">
                <button class="button button-tertiary button-extend text-uppercase">Submit</button>
            </div>
            <label for="#">Allow Access To</label>
            <label for="AllowAccessIdeas" class="checkbox-primary checkbox-allow-access">
                <input id="AllowAccessIdeas" type="checkbox">
                <span>Ideas</span>
            </label>
            <label for="AllowAccessContent" class="checkbox-primary checkbox-allow-access">
                <input id="AllowAccessContent" type="checkbox">
                <span>Content</span>
            </label>
            <label for="AllowAccessTasks" class="checkbox-primary checkbox-allow-access">
                <input id="AllowAccessTasks" type="checkbox">
                <span>Tasks</span>
            </label>
        </fieldset>
        -->

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="sidemodal-footer">
        <div class="form-group">
            <button class="button button-extend text-uppercase" id="add-calendar-button">
                Create Calendar
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
//-- code here
</script>