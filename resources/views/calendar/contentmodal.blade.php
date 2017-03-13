<div id="addContentModal" class="sidemodal medium">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create Content</h4>

                <h5>Add a quick content piece to your calendar, you'll be able to edit it later.</h5>
            </div>
            <div class="col-md-6 text-right" id="content-menu">
                <button class="button button-primary button-small text-uppercase" id="add-content-button">Add Content
                </button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>

            <div class="col-md-12">
                <div id="content-status-alert" class="alert hidden alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="content-status-text"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <div class="input-form-group">
            <label for="#">Content Title</label>
            <input type="text" name="content-title" id="content-title" class="input" placeholder="Enter Content Title">
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-form-group">
                    <label for="dueDate">Due Date</label>
                    <input class="input-calendar datetimepicker input form-control" id="content-due-date"
                           name="content-due-date" type="text" value="">
                </div>
            </div>
        </div>

        <div class="input-form-group">
            <label for="#">Content Type</label>

            <div class="select">
                {!! Form::select('content-type-id', $contenttypedd, @isset($content)? $content->content_type_id : ''  , array('class' => 'input selectpicker form-control', 'id' => 'content-type-id', 'data-live-search' => 'true', 'title' => 'Choose Content Type')) !!}
            </div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    </div>
</div>