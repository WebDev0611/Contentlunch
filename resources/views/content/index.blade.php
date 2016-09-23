@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">
            <div class="panel-header">
                <h4 class="panel-container-title">{{$countContent}} Content Items</h4>
            </div>
            <div class="panel-container-options hide">
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 text-right">
                                        <label class="select-horizontal-label">Show:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="select select-small extend">
                                            <select name="#" id="#">
                                                <option value="#">All Types</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 text-right">
                                        <label class="select-horizontal-label">By:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="select select-small extend">
                                            <select name="#" id="#">
                                                <option value="#">Any one</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 text-right">
                                        <label class="select-horizontal-label">Campaign:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="select select-small extend">
                                            <select name="#" id="#">
                                                <option value="#">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="create-link-dropdown">
                            <a href="#" data-toggle="dropdown">
                                ALL FILTERS
                                <i class="caret"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="#">Do Something</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-share"></i>
                    PUBLISHED
                </h4>
                @if(count($published) > 0)
                    @foreach($published as $pub)
                    <div class="create-panel-table">
                        <div class="create-panel-table-cell">
                            <img src="/images/avatar.jpg" alt="" class="create-image">
                        </div>
                        <div class="create-panel-table-cell">
                            <h5 class="dashboard-tasks-title">
                                {{ $pub->title }}
                            </h5>
                            <span class="dashboard-members-text small">
                                {{ strtoupper($pub->created_at->diffForHumans()) }}
                            </span>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <i class="tooltip-icon large icon-arrange-mini" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lorem Ipsum"></i>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <span class="dashboard-performing-text small">
                                UPDATED: <strong>{{ $pub->updated_at->format('m/d/Y') }}</strong>
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info" role="alert"><p>No Published Content at this moment.</p></div>
                @endif

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>

            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-share"></i>
                    READY TO BE PUBLISHED
                </h4>

                @if (count($readyPublished) > 0)
                    @foreach($readyPublished as $pub)
                    <div class="create-panel-table border-left">
                        <div class="create-panel-table-cell">
                            <img src="/images/avatar.jpg" alt="" class="create-image">
                        </div>
                        <div class="create-panel-table-cell">
                            <h5 class="dashboard-tasks-title">
                                {{ $pub->title }}
                            </h5>
                            <span class="dashboard-members-text small">
                                {{ strtoupper($pub->created_at->diffForHumans()) }}
                            </span>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <a href="{{ route('editContent', $pub->id) }}"
                               class="tooltip-icon large icon-arrange-mini"
                               data-toggle="tooltip"
                               data-placement="top"
                               title=""
                               data-original-title="Lorem Ipsum">
                            </a>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <span class="dashboard-performing-text small">
                                UPDATED: <strong>{{ $pub->updated_at->format('m/d/Y') }}</strong>
                            </span>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <i  class="create-panel-spaceship icon-spaceship-circle open-launch-menu"
                                data-content="{{ $pub->id }}"
                                data-toggle="modal"
                                data-target="#launch">
                            </i>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info" role="alert"><p>No Content that is ready for publishing at this moment.</p></div>
                @endif

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>
            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    <i class="icon-share"></i>
                    BEING WRITTEN / EDITED
                </h4>

                @if(count($written) > 0)
                    @foreach($written as $pub)
                    <div class="create-panel-table">
                        <div class="create-panel-table-cell">
                            <img src="/images/avatar.jpg" alt="" class="create-image">
                        </div>
                        <div class="create-panel-table-cell">
                            <h5 class="dashboard-tasks-title">
                                {{ $pub->title }}
                            </h5>
                            <span class="dashboard-members-text small">
                                {{ strtoupper($pub->created_at->diffForHumans()) }}
                            </span>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <i class="tooltip-icon large icon-arrange-mini" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lorem Ipsum"></i>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <span class="dashboard-performing-text small">
                                UPDATED: <strong>{{ $pub->updated_at->format('m/d/Y') }}</strong>
                            </span>
                        </div>
                        <div class="create-panel-table-cell text-right">
                            <div class="create-dropdown">
                                <button type="button" class="button button-action" data-toggle="dropdown">
                                    <i class="icon-add-circle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="javascript:;" onclick="location.href='/edit/{{$pub->id}}';">Write It</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info" role="alert"><p>No Content being written at this moment.</p></div>
                @endif

                <div class="create-panel-table hide">
                    <div class="create-panel-table-cell text-center">
                        <a href="#">13 More - Show All</a>
                    </div>
                </div>
            </div>
        </div>
        <aside class="panel-sidebar">
            <div class="panel-header">
                <h4 class="panel-sidebar-title">Ideas activity feed</h4>
            </div>
            <div class="panel-container">
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        <p class="plan-activity-text">
                            Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                            Etiam eget dolor...
                        </p>
                    </div>
                </div>
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        <p class="plan-activity-text">
                            Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                            Etiam eget dolor...
                        </p>
                    </div>
                </div>
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-icon">
                        <i class="icon-edit"></i>
                    </div>
                    <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                    </div>
                </div>
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        <p class="plan-activity-text">
                            Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                            Etiam eget dolor...
                        </p>
                        <div class="plan-activity-dropdown">
                            <button type="button" class="button button-action" data-toggle="dropdown">
                                <i class="icon-add-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="#">Write It</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

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
                            Here are the content connections you can push out to, check the ones you want, and
                            click launch and they go out in real time to be published.
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

                        <div class="form-group text-center">
                            <a href="#" class="link-gray">
                                ADD NEW
                                <i class="icon-add"></i>
                            </a>
                        </div>
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
                            <img src="/images/avatar.jpg" alt="#" class="create-image">
                            <h4>Blog post on online banking</h4>
                        </div>
                        <p class="text-gray">IS NOW PUBLISHED TO:</p>
                        <div class="modal-social">
                            <span>
                                <i class="icon-facebook-official"></i>
                            </span>
                            <span>
                                <i class="icon-trello"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend">Go To Dashboard!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    (function() {

        var selectedContentId = null;

        $('#launchButton').click(function(event) {
            publishContent(selectedContentId);
            showLaunchCompleted();
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

        function showLaunchCompleted() {
            $('#launchCompleted').modal('show');
        }

    })();
</script>
@stop