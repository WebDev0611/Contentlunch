    <div id="filterModal" class="sidemodal large">
        <div class="sidemodal-header">
            <h4 class="sidemodal-header-title">Filter Calendar</h4>
            <button class="sidemodal-close" data-dismiss="modal">
                <i class="icon-remove"></i>
            </button>
        </div>
        <div class="sidemodal-container">
            <a href="#" class="sidemodal-link text-right text-uppercase">Clear Filters</a>


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
                <div class="input-group">
            <select class="multipleSelect" multiple name="language">
                <option value="Bangladesh">Bangladesh</option>
                <option selected value="Barbados">Barbados</option>
                <option selected value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
            </select>
                </div>
            </div>

            <div class="form-group">
                <label for="#">Content Type</label>
                <div class="input-group">
                    <input type="text" class="input" placeholder="Facebook, Twitter" id="filter-content-types">
                    <span class="button-input-group">
                      <button class="button button-outline-secondary button-small" type="button" id="open-content-type-modal">
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
                <button class="button button-outline-secondary button-extend text-uppercase">
                    Save as new Calendar
                </button>
            </div>
        </div>
    </div>