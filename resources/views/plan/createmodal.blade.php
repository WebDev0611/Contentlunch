<div id="createIdea" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create Idea</h4>
                <div id="idea-status-alert" class="alert hidden">
                    <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div id="idea-status-text"></div>
                </div>
            </div>
            <div class="col-md-6 text-right" id="idea-menu">
                <button class="button button-primary button-small text-uppercase save-idea">Save</button>
                <button type="button" class="button button-outline-primary button-small park-idea">PARK</button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="input-form-group">
            <label for="#">CONCEPT NAME</label>
            <input type="text" class="input idea-name" placeholder="Enter your concept name">
        </div>
        <div class="input-form-group">
            <label for="#">EXPLAIN YOUR IDEA</label>
            <textarea rows="4" class="input idea-text" placeholder="Explain idea in a paragraph or so"></textarea>
        </div>
        <div class="col-md-4 input-form-group no-padding">
            <label>ASSIGN TO CALENDAR</label>
            @php
                $calendarsDropdown = \App\Account::selectedAccount()->calendarsDropdown();
            @endphp
            {!!
                Form::select(
                    'calendar_id',
                    $calendarsDropdown,
                    $calendarsDropdown ? min(array_keys($calendarsDropdown)) : '',
                    ['id' => 'idea-calendar-id', 'class' => 'input selectpicker form-control', 'title' => 'Choose Calendar']
                )
            !!}
        </div>
        <div class="clearfix"></div>
        <div class="input-form-group">
            <label for="#">TAGS</label>
            <input type="text" class="input idea-tags" placeholder="Enter comma separated tags">
        </div>

        <div class="form-group">
            <fieldset class="form-fieldset clearfix">
                <legend class="form-legend">Collaborators</legend>
                <ul class="images-list pull-left" id='ideas-collaborator-list'>

                </ul>

                <button type="button" id='open-collab-modal' class="button button-action large pull-right">
                    <i class="icon-add-circle"></i>
                </button>
            </fieldset>
        </div>

        <div class="form-delimiter">
            <span>
                <em>Selected Content</em>
            </span>
        </div>
        <div id="selected-content">
        </div>
        <!--
        <div class="tombstone tombstone-horizontal tombstone-active clearfix">
            <div class="tombstone-image">
                <img src="http://i.imgur.com/MYB6HjU.jpg" alt="">
            </div>
            <div class="tombstone-container">
                <h3>Google self-driving car is tested on California highways</h3>
                <p>
                    Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of
                    electric cars, including a top of the line Tesla, went on...
                </p>
            </div>
        </div>

        <div class="tombstone tombstone-horizontal tombstone-active clearfix">
            <div class="tombstone-image">
                <img src="http://i.imgur.com/MYB6HjU.jpg" alt="">
            </div>
            <div class="tombstone-container">
                <h3>Google self-driving car is tested on California highways</h3>
                <p>
                    Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of
                    electric cars, including a top of the line Tesla, went on...
                </p>
            </div>
        </div>
        -->

    </div>
</div>

