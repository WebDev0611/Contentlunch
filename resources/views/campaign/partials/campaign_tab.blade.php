<div class="inner toppadded">
    @include('partials.error')

    <div class="input-form-group">
        <label for="#">CAMPAIGN TITLE</label>
        {!!
            Form::text(
                'title',
                @isset($campaign) ? $campaign->title : '',
                [
                    'placeholder' => 'Enter campaign title',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-title',
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
                            'start_date',
                            @isset($campaign) ? $campaign->start_date : '',
                            [
                                'class' => 'input form-control',
                                'id' => 'start-date',
                                'name' => 'start_date',
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
                            'end_date',
                            @isset($campaign) ? $campaign->end_date : '',
                            [
                                'class' => 'input form-control',
                                'id' => 'end-date',
                            ])
                    !!}
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
                        'type',
                        $campaignTypesDropdown,
                        @isset($campaign)? $campaign->campaign_type_id : '',
                        [
                            'class' => 'input selectpicker form-control',
                            'id' => 'campaign-types',
                            'title' => 'Choose Campaign Type',
                        ])
                !!}
            </div>
        </div>
        {{--
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">CAMPAIGN BUDGET</label>
                <input type="text" name="campaign-budget" class="input input-larger" placeholder="Enter budget in USD" value="">
            </div>
        </div>
        --}}
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">STATUS</label>
                @php
                    $options = [
                        '0' => 'Inactive',
                        '1' => 'Active',
                        '2' => 'Paused',
                    ];
                @endphp
                {!!
                    Form::select(
                        'status',
                        $options,
                        '0',
                        [
                            'placeholder' => 'Set Campaign status',
                            'class' => 'input',
                        ]
                    )
                !!}
            </div>
        </div>
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN DESCRIPTION</label>
        {!!
            Form::textarea(
                'description',
                @isset($campaign) ? $campaign->description : '',
                [
                    'placeholder' => 'Enter Campaign Description',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-description',
                ])
        !!}
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN GOALS</label>
        {!!
            Form::textarea(
                'goals',
                @isset($campaign)? $campaign->goals : '',
                [
                    'placeholder' => 'Enter Campaign Goals',
                    'class' => 'input input-larger form-control',
                    'id' => 'campaign-goals'
                ])
        !!}
    </div>

    <!-- Attachments -->
    <div class="form-delimiter">
        <span>
            <em>Attachments</em>
        </span>
    </div>

    @if (!$attachments->isEmpty())
    <div class="input-form-group">
        <ul>
            @foreach ($attachments as $file)
            <li><a href="{{ $file->filename }}">{{ $file->name }}</a></li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- <div class="input-form-group @if (!$isCollaborator) hide @endif"> --}}
    <div class="input-form-group">
        <div class="dropzone" id='attachment-uploader'>
        </div>
    </div>

   @if ($campaign->collaborators->isEmpty() && !$campaign->id)
        {{ Form::hidden('collaborators', Auth::id()) }}
    @else
        {{ Form::hidden('collaborators', $campaign->present()->collaboratorsIDs) }}
    @endif

    @if (!$campaign->id)
        {{ Form::hidden('tasks') }}
    @endif
</div>