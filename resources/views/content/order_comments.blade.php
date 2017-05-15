@extends('layouts.master')

@section('content')
    <div class="workspace">

        <div class="panel clearfix col-md-8 col-md-offset-2 comments-panel">

            @if($comments->isEmpty())
                <div class="alert alert-info" role="alert">No comments for this order yet.</div>
            @else

                <h3>{{$comments[0]->order_title}}</h3>

                @foreach($comments as $comment)

                    <div class="col-md-6 @if($comment->from_client) pull-right @endif">

                        <div class="panel panel-default single-comment @if(!$comment->from_client) panel-info @else panel-warning @endif">
                            <div class="panel-heading">
                                <h3 class="panel-title">

                                    @if($comment->from_client) <strong>You</strong>
                                    @elseif($comment->writer_name)
                                        <strong>{{$comment->writer_name}}</strong> (Writer)
                                    @elseif($comment->editor_name)
                                        <strong>{{$comment->editor_name}}</strong> (Editor)
                                    @endif

                                    <span class="pull-right">{{date('Y-m-d H:i', strtotime($comment->timestamp))}}</span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                {{$comment->note}}
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                @endforeach

            @endif

        </div>

    </div>
@stop