@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix order" data-order-id="{{$order->id}}">

            <!-- Main Pane -->
            <div class="panel-main">
                <!-- Panel Header -->
                <div class="panel-header">
                    <div class="panel-options">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Content Order</h4>
                            </div>
                            {{ Form::open(['url' => '/create/new']) }}
                            {{ Form::hidden('title', $order->preview_title) }}
                            {{ Form::hidden('body', $order->preview_text) }}
                            {{ Form::hidden('content_type', 0) }}
                            <div class="col-md-6 text-right">
                                <div class="head-actions">
                                    <a
                                        @if($order->status !== "Pending Approval") disabled @endif
                                        class="button button-outline-secondary button-small delimited approve"
                                        name="action"
                                        value="written_content"
                                        @if($order->status !== "Approved")href="/content/orders/approve/{{ $order->order_id }}" @endif>
                                        <img src="/images/icons/check-large.svg" alt="Approve">@if($order->status === "Approved") APPROVED @else APPROVE @endif
                                    </a>


                                    <button
                                            type="submit"
                                            class="button button-outline-secondary button-small delimited launch"
                                            name="action"
                                            value="written_content">
                                        <img src="/images/icons/spaceship-circle.svg" alt="Launch"> LAUNCH
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div> <!-- End Panel Header -->

                <!-- Panel Container -->
                <div class="panel-container padded relative">
                    <!-- Stages widget -->
                    @php
                        $status = $order->status;
                    @endphp


                    <div class="inner">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-forms" id="formError">
                                <p><strong>Oops! We had some errors:</strong>
                                    <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="content_type">WRITER</label>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <img class="img-circle" id="writer-img" src="{{ $order->writer->photo }}" alt="{{ $order->writer->name }}">
                                        </div>
                                        <div class="col-sm-6">
                                            {{ $order->writer->name }} <br />
                                            {{ $order->writer->location }} <br />
                                            @for($i=0; $i<$order->writer->rating; $i++)
                                                <img class="rating-star" src="/images/icons/star.svg" alt="">
                                            @endfor

                                            <div class="clearfix"></div>

                                            @php
                                                $writerInfo = '<b>Education</b>: ' . $order->writer->educationlevel . '<br>' .
                                                              '<b>Quote</b>: ' . $order->writer->quote . '<br>' .
                                                              '<b>Specialties</b>: ' . $order->writer->specialties . '<br>' .
                                                              '<b>Summary</b>: ' . $order->writer->summary;
                                            @endphp

                                            <button type="button" data-toggle="popover" data-trigger="focus" title="{{ $order->writer->name }}" class="btn btn-sm btn-default writer-more"
                                                    data-content="{{ $writerInfo }}">
                                                More about writer
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="dueDate">STATUS</label>
                                    @if($order->status === "Approved")
                                        <b>APPROVED</b> ({{ Carbon\Carbon::parse($order->approved)->diffForHumans() }})
                                    @else
                                        <b>{{$order->status}}</b>
                                    @endif
                                    {{--<input class="input-calendar input form-control" type="text" readonly value="{{ $order-> }}">--}}
                                </div>
                            </div>
                        </div>

                        <div class="input-form-group">
                            <label for="title">TITLE</label>
                            <p>{{$order->title}}</p>
                        </div>

                        <div class="editor" style="min-height: 530px; margin-bottom: 25px;">
                            <label>CONTENT BODY</label>
                            @php
                                $orderOptions = [
                                    'placeholder' => 'Enter content',
                                    'class' => 'input input-larger form-control wysiwyg',
                                    'id' => 'title'
                                ];
                            @endphp

                            {!! @isset($order->preview_text) ? $order->preview_text : '<div class="alert alert-info alert-forms" role="alert"><p>Content has not been submitted for this order yet..</p></div>' !!}
                        </div>

                    </div>  <!-- End Panel Container -->

                </div> <!-- End Main Pane -->

            </div>  <!-- End Panel Container -->

            <!-- Side Pane -->
            <aside class="panel-sidebar comment-sidebar" id='editor-panel-sidebar'>
                <div class="panel-header">
                    <ul class="panel-tabs withborder">
                        <li class="active">
                            <a href="#sidetab-tasks" role='tab' data-toggle='tab'>Comments</a>
                        </li>
                    </ul>
                </div>

                <content-order-comment-list order-id="{{$order->order_id}}"></content-order-comment-list>

                <!--
                <div class="panel-body">
                    <ul class="comment">

                        <li class="right clearfix">
                            <span class="comment-img pull-right">
                                <img src="{{$order->writer->photo}}" alt="User Avatar" class="img-circle"/>
                            </span>
                            <div class="comment-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">{{ $order->writer->name }}</strong>
                                    <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> 4 mins ago
                                    </small>
                                </div>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sagittis vitae nisl non laoreet. Aliquam non nisi at leo sollicitudin efficitur. Mauris vel eros ornare, vestibulum turpis in
                                </p>
                            </div>
                        </li>

                        <li class="left clearfix">
                            <span class="comment-img pull-left">
                                <img src="{{ \Auth::user()->present()->profile_image }}" alt="User Avatar" class="img-circle"/>
                            </span>
                            <div class="comment-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">{{ \Auth::user()->present()->name }}</strong>
                                    <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> 2 hours ago
                                    </small>
                                </div>
                                <p>
                                    Lasdaslkjdosal
                                </p>
                            </div>
                        </li>

                        <li class="right clearfix">
                            <span class="comment-img pull-right">
                                <img src="/images/cl-avatar2.png" alt="User Avatar" class="img-circle"/>
                            </span>
                            <div class="comment-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">{{ $order->writer->name }}</strong>
                                    <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> 4 days ago
                                    </small>
                                </div>
                                <p>
                                    LAsldlasdasd
                                </p>
                            </div>
                        </li>

                    </ul>
                </div>
-->

            </aside> <!-- End Side Pane -->

        </div>
    </div>
@stop

@section('styles')
    <style>
        a.approve img, button.launch img{
            width: 20px;
            margin-right: 2px;
        }

        #writer-img{
            width: 66px;
        }

        .rating-star{
            width: 12px;
        }

        .comment-sidebar{

        }
        .comment
        {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .comment li
        {
            margin-bottom: 10px;
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .comment li.left .comment-body
        {
            margin-left: 60px;
        }

        .comment li.right .comment-body
        {
            margin-right: 60px;
        }


        .comment li .comment-body p
        {
            margin: 0;
            color: #777777;
        }

        .alert.margin-20{
            margin: 20px;
        }

        div.popover{
            width: 300px;
        }

        .popover-content {
            line-height: 23px;
        }

        .btn.writer-more {
            margin-top: 10px;
        }
    </style>
@stop


@section('scripts')
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                'html': true
            });
        });
    </script>
@stop
