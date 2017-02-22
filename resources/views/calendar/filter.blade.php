<div id="filterModal" class="sidemodal">
    <div class="sidemodal-header">
        <h4 class="sidemodal-header-title">Filter Calendar</h4>
        <button class="sidemodal-close" data-dismiss="modal">
            <i class="icon-remove"></i>
        </button>
    </div>
    <div class="sidemodal-container">
        <a href="#" id="clear-filters-btn" class="sidemodal-link text-right text-uppercase">Clear Filters</a>


        <!--
        <div class="form-group">
            <label for="#">Calendars</label>
            <div class="input-group">
                <input type="text" class="input" placeholder="All">
                <span class="button-input-group">
                  <button class="button button-outline-secondary button-small" type="button">
                      <i class="icon-add"></i>
                  </button>
                </span>
            </div>
        </div>
        -->
        <div class="form-group">
            <label for="#">Content Type</label>

            <div class="input-group filter-content-type">
                <select class="multipleSelect" multiple data-user-option-allowed="false">
                    <option id="filter-type-tasks" value="tasks">Tasks</option>
                    <option id="filter-type-ideas" value="ideas">Ideas</option>

                    @foreach($available_content_types as $content_type)
                        <option id="filter-type-id-{{$content_type->id}}"
                                value="{{$content_type->id}}">{{$content_type->name}}</option>
                    @endforeach
                </select>

                <span class="button-input-group" >
                      <button class="button button-outline-secondary button-small" type="button" id="filter-plus-btn">
                          <i class="icon-add"></i>
                      </button>
                    </span>
            </div>
        </div>

        <!--
        <div class="input-form-group">
            <label for="#">Team Members</label>
            <input type="text" class="input" placeholder="All">
        </div>
        -->
    </div>
    <div class="sidemodal-footer">
        <div class="form-group">
            <button class="button button-extend text-uppercase" id="apply-filters">
                Apply Filters
            </button>
        </div>
        <div class="form-group">
            <button class="button button-outline-secondary button-extend text-uppercase" id="save-as-new">
                Save as new Calendar
            </button>
        </div>
    </div>
</div>