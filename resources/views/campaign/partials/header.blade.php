<div class="panel-options">
    <div class="row">
        <div class="col-md-offset-6 col-md-6 text-right">
            <div class="head-actions">
                {{
                    Form::submit('SAVE', [
                        'class' => 'button button-outline-secondary button-small delimited',
                    ])
                }}


                <div class="btn-group">
                    {{--
                    <button
                        type="button"
                        class="button button-small">DONE</button>
                        --}}

                    <button
                        type="button"
                        class="button button-small dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        ACTIONS

                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right">
                        @if(!$campaign->isActive()) <li><a href="{{ route('campaigns.activate', $campaign) }}">Activate</a></li> @endif
                        @if(!$campaign->isPaused()) <li><a href="{{ route('campaigns.park', $campaign) }}">Park</a></li> @endif
                        @if(!$campaign->isInactive()) <li><a href="{{ route('campaigns.deactivate', $campaign) }}">Deactivate</a></li> @endif
                        <li><a class="campaign-delete" href="{{ route('campaigns.destroy', $campaign) }}">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>