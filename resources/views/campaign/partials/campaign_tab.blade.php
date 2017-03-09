<div class="inner toppadded">
    @include('partials.error')

    <div class="input-form-group">
        <label for="#">CAMPAIGN TITLE *</label>
        @php
            $titleOptions = \App\Helpers::isCollaborator([
                'placeholder' => 'Enter campaign title',
                'class' => 'input input-larger form-control',
                'id' => 'campaign-title',
            ], $isCollaborator);
        @endphp
        {!! Form::text('title', old('title'), $titleOptions) !!}
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">START DATE</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    @php
                        $startDateOptions = \App\Helpers::isCollaborator([
                            'class' => 'input form-control',
                            'id' => 'start-date',
                        ], $isCollaborator);
                    @endphp
                    {!! Form::text('start_date', old('start_date'), $startDateOptions) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="input-form-group">
                <label for="#">END DATE</label>
                <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    @php
                        $endDateOptions = \App\Helpers::isCollaborator([
                            'class' => 'input form-control',
                            'id' => 'end-date',
                        ], $isCollaborator);
                    @endphp
                    {!! Form::text('end_date', old('end_date'), $endDateOptions) !!}
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
                        old('campaign_type_id'),
                        \App\Helpers::isCollaborator([
                            'class' => 'input selectpicker form-control',
                            'id' => 'campaign-types',
                            'title' => 'Choose Campaign Type',
                        ], $isCollaborator))
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

                    $statusOptions = \App\Helpers::isCollaborator([
                        'placeholder' => 'Set Campaign status',
                        'class' => 'input',
                    ], $isCollaborator);
                @endphp
                {!! Form::select('status', $options, old('status'), $statusOptions) !!}
            </div>
        </div>
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN DESCRIPTION</label>
        @php
            $descriptionOptions = \App\Helpers::isCollaborator([
                'placeholder' => 'Enter Campaign Description',
                'class' => 'input input-larger form-control',
                'id' => 'campaign-description',
            ], $isCollaborator);
        @endphp
        {!! Form::textarea('description', old('description'), $descriptionOptions) !!}
    </div>
    <div class="input-form-group">
        <label for="#">CAMPAIGN GOALS</label>
        @php
            $goalsOptions = \App\Helpers::isCollaborator([
                'placeholder' => 'Enter Campaign Goals',
                'class' => 'input input-larger form-control',
                'id' => 'campaign-goals'
            ], $isCollaborator);
        @endphp
        {!! Form::textarea('goals', old('goals'), $goalsOptions) !!}
    </div>

    <div class="form-delimiter">
        <span>
            <em>Content Pieces</em>
        </span>
    </div>

    <div class="input-form-group">
        @forelse ($availableContents as $content)
            <div class="create-panel-table border-left">
                <label class="create-panel-table-cell cell-size-5">
                    @php
                        $newContent = collect(old('newContent'));
                        $checked = $newContent->search($content->id) !== false;
                    @endphp
                    <input type="checkbox"
                           name="newContent[]"
                           value="{{ $content->id }}"
                           @if($checked) checked @endif >
                </label>
                <div class="create-panel-table-cell cell-size-5">
                    @include('content.partials.avatar')
                </div>
                <div class="create-panel-table-cell cell-size-60">
                    <h5 class="dashboard-tasks-title">
                        <a href="{{ route('editContent', $content) }}">{{ $content->present()->title }}</a>
                    </h5>
                    <span class="dashboard-members-text small">
                        {{ strtoupper($content->present()->createdAt) }}
                    </span>
                </div>
                <div class="create-panel-table-cell text-center cell-size-5">
                    <i class="tooltip-icon large {{ $content->present()->contentIcon }}"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="{{ $content->present()->contentType }}"
                        data-original-title="{{ $content->present()->contentType }}"></i>
                </div>
                <div class="create-panel-table-cell cell-size-15 text-right">
                    <span class="dashboard-performing-text small @if ($content->isDueDateCritical()) critical @endif">
                        DUE: <strong>{{ strtoupper($content->present()->dueDateFormat) }}</strong>
                    </span>
                </div>
            </div>
        @empty
            <div class="alert alert-info alert-forms">
                No new content pieces available to be selected
            </div>
        @endforelse
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

    <div class="input-form-group @if (!$isCollaborator) hide @endif">
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