@extends('layouts.master')

@section('content')
    <div class="workspace">
        <div class="panel clearfix">
            <div class="panel-main">
                @include('content.partials.dashboard.panel_tabs')

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
                        INACTIVE
                    </h4>

                    <div>
                        @forelse ($inactiveCampaigns as $campaign)
                            @include('content.partials.campaigns.campaign_row')
                        @empty
                            <div class="alert alert-info alert-forms" role="alert">
                                <p>No Inactive Campaigns at this moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="create-panel-container no-padding">
                    <h4 class="create-panel-heading">
                        PAUSED
                    </h4>

                    <div>
                        @forelse ($pausedCampaigns as $campaign)
                            @include('content.partials.campaigns.campaign_row')
                        @empty
                            <div class="alert alert-info alert-forms" role="alert">
                                <p>No Paused Campaigns at this moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

