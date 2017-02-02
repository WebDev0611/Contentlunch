<div class="inner toppadded">
    <div class="input-form-group">
        <label for="#">CAMPAIGN TITLE</label>
        {!!
            Form::text(
                'campaign-title',
                @isset($campaign) ? $campaign->title : '',
                [
                    'placeholder' => 'Enter campaign title',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-title'
                ])
        !!}
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">START DATE</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    {!!
                        Form::text(
                            'start-date',
                            @isset($campaign) ? $campaign->start_date : '',
                            [
                                'class' => 'input form-control',
                                'id' => 'start-date'
                            ])
                    !!}
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">END DATE</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    {!!
                        Form::text(
                            'end-date',
                            @isset($campaign) ? $campaign->end_date : '',
                            [
                                'class' => 'input form-control',
                                'id' => 'end-date'
                            ])
                    !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">OTHER DATE 1</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input"  id="other-date-1" placeholder="Select date">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">OTHER DATE 2</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" id="other-date-2" placeholder="Select date">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">CAMPAIGN TYPE</label>
                {!!
                    Form::select(
                        'campaign-type',
                        $campaigntypedd,
                        @isset($campaign)? $campaign->campaign_type_id : '',
                        [
                            'class' => 'input selectpicker form-control',
                            'id' => 'campaign-types',
                            'title' => 'Choose Campaign Type'
                        ])
                !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">CAMPAIGN BUDGET</label>
                <input type="text" name="campaign-budget" class="input input-larger" placeholder="Enter budget in USD" value="">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">STATUS</label>
                <select name="" class="input" >
                    <option selected disabled>Set campaign status</option>
                    <option>Active</option>
                    <option>Paused</option>
                    <option>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN DESCRIPTION</label>
        {!!
            Form::textarea(
                'campaign-description',
                @isset($campaign) ? $campaign->description : '',
                [
                    'placeholder' => 'Enter Campaign Description',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-description'
                ])
        !!}
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN GOALS</label>
        {!!
            Form::textarea(
                'campaign-goals',
                @isset($campaign)? $campaign->goals : '',
                [
                    'placeholder' => 'Enter Campaign Goals',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-goals'
                ])
        !!}
    </div>
    <div class="input-form-group">
        <label for="#">TAGS</label>
        <input type="text" name="campaign-tags" class="input input-larger" placeholder="Enter one or more tags">
    </div>
    <!-- Attachments -->
    <div class="form-delimiter">
        <span>
            <em>Attachments</em>
        </span>
    </div>
    <div class="input-form-group">
        <div class="fileupload">
            <i class="icon-link picto"></i>
            <p class="msgtitle">Click to attach one or more files</p>
            <input type="file" class="input input-upload">
        </div>
    </div>
</div>