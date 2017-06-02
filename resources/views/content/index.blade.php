@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">
            @include('content.partials.dashboard.panel_tabs')

            <content-filter content-count='{{ $countContent }}'></content-filter>

            @include('elements.freemium-alert', ['restriction' => 'launch 5 content pieces'])

            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-connect"></i>
                    PUBLISHED
                </h4>

                @forelse ($published as $content)
                    @include('content.partials.dashboard.published_content_loop')
                @empty
                    <div class="alert alert-info alert-forms" role="alert">
                        <p>No Published Content at this moment.</p>
                    </div>
                @endforelse

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>

            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-content-alert"></i>
                    READY TO BE PUBLISHED
                </h4>

                @forelse ($readyPublished as $content)
                    @include('content.partials.dashboard.ready_published_content_loop')
                @empty
                    <div class="alert alert-info alert-forms" role="alert">
                        <p>No Content that is ready for publishing at this moment.</p>
                    </div>
                @endforelse

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>
            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-edit-content"></i>
                    BEING WRITTEN / EDITED
                </h4>

                @forelse ($written as $content)
                    @include('content.partials.dashboard.written_content_loop')
                @empty
                    <div class="alert alert-info alert-forms" role="alert"><p>No Content being written at this moment.</p></div>
                @endforelse

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>
        </div>
        {{--
        <aside class="panel-sidebar">
            @include('content.partials.dashboard.ideas-sidebar')
        </aside>
        --}}
    </div>
</div>

@can('guests-denied')
<div id="launch" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">LAUNCH CONTENT</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <p class="text-gray text-center">
                            Here are your content connections. To launch content, simply check the connections, click launch
                            and your content will be published right now!
                        </p>

                        @foreach ($connections as $conn)
                        <label for="connection-{{ $conn->id }}" class="checkbox-tag">
                            <input
                                id="connection-{{ $conn->id }}"
                                class='connection-checkbox'
                                type="checkbox">
                            <span>{{ $conn->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button
                            class="button button-primary text-uppercase button-extend"
                            id='launchButton'>

                            LAUNCH
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="launchCompleted" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">CONTENT LAUNCHED</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <i class="modal-icon-success icon-check-large"></i>
                        <div class="form-group">
                            <img src="/images/cl-avatar2.png" alt="#" class="create-image">
                            <h4></h4>
                        </div>
                        <p class="text-gray">IS NOW PUBLISHED TO:</p>
                        <div class="modal-social">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <a href="{{ route('contents.index') }}" class="button text-uppercase button-extend">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@stop

@section('scripts')
<script>
    (function() {

        var selectedContentId = null;

        var ContentModel = Backbone.Model.extend({});

        var LaunchCompletedView = Backbone.View.extend({
            initialize: function(options) {
                this.publishedConnections = options.publishedConnections;
                this.render();
            },

            render: function() {
                var title = this.model.get('title');

                this.addConnectionIcons();
                this.$el.find('h4').text(title);
                this.$el.modal('show');
            },

            addConnectionIcons: function() {
                this.publishedConnections.forEach(function(connectionName) {
                    var element = this.createConnectionIcon(connectionName);
                    this.$el.find('.modal-social').append(element);
                }.bind(this));
            },

            createConnectionIcon: function(connectionName) {
                return $('<span />', { class: 'icon-social-' + connectionName });
            },
        });

        $('#launchButton').click(function(event) {
            publishContent(selectedContentId)
                .then(showLaunchCompleted)
                .catch(showFeedbackError);
        });

        $('.open-launch-menu').click(function(event) {
            selectedContentId = $(this).data('content');
        });

        function publishContent() {
            var connections = $('.connection-checkbox:checked')
                .toArray()
                .map(function(checkbox) {
                    return checkbox.id.split('-')[1];
                })
                .join(',');

            return $.ajax({
                method: 'get',
                url: '/content/multipublish/' + selectedContentId + '?connections=' + connections
            });
        }

        function showLaunchCompleted(response) {
            var content = new ContentModel(response.content);
            var view = new LaunchCompletedView({
                el: '#launchCompleted',
                model: content,
                publishedConnections: response.published_connections,
            });
        }

        function showFeedbackError(response) {
            swal('Error!', response.responseJSON.data, 'error');
        }

        //tasks
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }

    })();
</script>
@stop