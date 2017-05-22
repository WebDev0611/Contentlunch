@extends('layouts.master')

@section('content')
    <div class="workspace">
        <div class="panel clearfix col-md-8 col-md-offset-2 comments-panel">

            <content-order-comment-list order-id="{{$orderId}}"></content-order-comment-list>

        </div>
    </div>
@stop