@extends('layouts.master')

@section('content')
    <div class="workspace">
        <div class="panel clearfix">
            <div class="panel-main">
                @include('content.partials.dashboard.panel_tabs')
                @include('elements.freemium-alert', ['restriction' => 'launch 5 content pieces'])

                <div class="create-panel-container">
                    <h4 class="create-panel-heading">
                        <i class="icon-share"></i>
                        ARCHIVED CONTENT
                    </h4>

                    @forelse ($archived as $content)
                        <div class="create-panel-table">
                            <div class="create-panel-table-cell cell-size-5">
                                @include('content.partials.avatar')
                            </div>
                            <div class="create-panel-table-cell cell-size-75">
                                <h5 class="dashboard-tasks-title">
                                    {{ $content->present()->title }}
                                </h5>
                                <span class="dashboard-members-text small">
                                    {{ strtoupper($content->present()->createdAt) }}
                                </span>
                            </div>
                            <div class="create-panel-table-cell text-center cell-size-5"
                                 data-toggle="tooltip"
                                 data-placement="top"
                                 title="{{ $content->present()->contentType }}"
                                 data-original-title="{{ $content->present()->contentType }}">

                                <i class="tooltip-icon large {{ $content->present()->contentIcon }}"></i>
                            </div>
                            <div class="create-panel-table-cell text-right cell-size-15">
                                <span class="dashboard-performing-text small">
                                    UPDATED: <strong>{{ strtoupper($content->present()->updatedAtFormat) }}</strong>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info alert-forms" role="alert"><p>No Archived Content at this moment.</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop
