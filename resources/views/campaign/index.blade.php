@extends('layouts.master')

@section('content')
<div class="workspace">

    <!-- Pannel Container -->
    <div class="panel clearfix">

        <!-- Main Pane -->
        <div class="panel-main">

            <!-- Content Tabs -->
            <ul class="panel-tabs centered even withborder">
                <li class='active'>
                    <a href="#contenttab-campaign" role="tab" data-toggle="tab">Campaign Editor</a>
                </li>
                <li class="">
                    <a href="#contenttab-content" role="tab" data-toggle="tab">Content</a>
                </li>
            </ul>

            <!-- Panel Container -->
            <div class="panel-container relative">

                <!-- Tab Content -->
                <div class="tab-content">

                    <!-- Tab1: Content -->
                    <div role="tabpanel" class="tab-pane" id="contenttab-content">
                        @include('campaign.partials.content_tab')
                    </div>

                    <!-- Tab2: Campaign Info -->
                    <div role="tabpanel" class="tab-pane active" id="contenttab-campaign">
                        @if(isset($campaign))
                            {{ Form::model($campaign, [ 'route' => [ 'campaigns.update', $campaign ], 'id' => 'campaign_editor' ]) }}
                        @else
                            {{ Form::open([ 'route' => 'campaigns.store', 'id' => 'campaign_editor' ]) }}
                        @endif
                            @include('campaign.partials.header')
                            @include('campaign.partials.campaign_tab')
                        {{ Form::close() }}
                    </div>

                </div> <!-- End Tab Content -->
            </div>  <!-- End Panel Container -->
        </div> <!-- End Main Pane -->

        <!-- Side Pane -->
        <aside class="panel-sidebar">
            @include('campaign.partials.sidebar')
        </aside> <!-- End Side Pane -->
    </div>  <!-- End Panel Container -->

</div>
<script>
    var campaign_types = {!! $campaignTypes !!};
    var campaign = {!! $campaign->toJson() !!};
</script>
@stop

@section('scripts')
<script src="{{ elixir('js/campaign.js', null) }}"></script>
@stop