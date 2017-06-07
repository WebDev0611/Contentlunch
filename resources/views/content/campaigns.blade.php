@extends('layouts.master')

@section('content')
    <div class="workspace">
        <div class="panel clearfix">
            <div class="panel-main">
                @include('content.partials.dashboard.panel_tabs')

                @include('elements.freemium-alert', ['restriction' => 'create only three campaigns'])

                <div class="create-panel-container no-padding">
                    <h4 class="create-panel-heading">
                        ACTIVE
                    </h4>

                    <div>
                        @forelse ($activeCampaigns as $campaign)
                            @include('content.partials.campaigns.campaign_row')
                        @empty
                            <div class="alert alert-info alert-forms" role="alert">
                                <p>No Active Campaigns at this moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="create-panel-container no-padding">
                    <h4 class="create-panel-heading">
                        IN PREPARATION
                    </h4>

                    <div>
                        @forelse ($inPreparationCampaigns as $campaign)
                            @include('content.partials.campaigns.campaign_row')
                        @empty
                            <div class="alert alert-info alert-forms" role="alert">
                                <p>No Inactive Campaigns at this moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

