@extends('layouts.master')

@section('content')
<div class="workspace">

    <!-- Pannel Container -->
    <div class="panel clearfix">

        <!-- Main Pane -->
        <div class="panel-main">

            <!-- Panel Header -->
            <div class="panel-header" id="create-campaign-form">
                <div class="panel-options">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Campaign editor</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="head-actions">
                                <button
                                    type="button"
                                    class="button button-outline-secondary button-small delimited"
                                    id="save-campaign-button">SAVE</button>

                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="button button-small">DONE</button>

                                    <button
                                        type="button"
                                        class="button button-small dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">

                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="#">Park</a></li>
                                        <li><a href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Panel Header -->

            <!-- Content Tabs -->
            <ul class="panel-tabs centered even withborder">
                <li class="active">
                    <a href="#contenttab-content" role="tab" data-toggle="tab">Content</a>
                </li>
                <li>
                    <a href="#contenttab-campaign" role="tab" data-toggle="tab">Campaign Info</a>
                </li>
            </ul>

            <!-- Panel Container -->
            <div class="panel-container relative">

                <!-- Tab Content -->
                <div class="tab-content">

                    <!-- Tab1: Content -->
                    <div role="tabpanel" class="tab-pane active" id="contenttab-content">
                        @include('campaign.partials.content_tab')
                    </div>

                    <!-- Tab2: Campaign Info -->
                    <div role="tabpanel" class="tab-pane" id="contenttab-campaign">
                        @include('campaign.partials.campaign_tab')
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
    var campaign_types = {!! $campaign_types !!};
    var campaign = {!! $campaign->toJson() !!};
</script>
@stop

@section('scripts')
<script src="/js/campaign.js"></script>
@stop