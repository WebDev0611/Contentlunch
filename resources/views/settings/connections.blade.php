@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                <!-- navigation -->
                @include('settings.partials.navigation')
            </div>

            @include('elements.freemium-alert')

            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="settings-import">
                            <div class="settings-import-container">
                                <!--
                                <input
                                    type="text"
                                    class="settings-import-input"
                                    placeholder="Quick search your list of friends">
                                -->

                                <div class="settings-import-action">
                                    <span>
                                        {{count($connections)}} connections,
                                        <strong>{{$activeConnectionsCount}} active</strong>
                                    </span>
                                    <button
                                        class="button button-small"
                                        data-target="#newConnection"
                                        data-toggle="modal"
                                        id="newConnectionButton">

                                        <i class="icon-add"></i>
                                        NEW CONNECTION

                                    </button>
                                </div>
                                <div class="settings-import-list">
                                    @include('settings.partials.connection_list')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <i class="icon-notification"></i>
                        <p>
                            Content items produced inside Content Launch are published (or launched) to your Content Connections and include Wordpress, Hubspot, Facebook and Twitter.
                            Many more will be added in the coming weeks. Use the list on the left to manage your connections.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newConnection" class="sidemodal large">
    {{ Form::open([ 'route' => 'connections.store']) }}
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">New Connection</h4>
            </div>
            <div class="col-md-6 text-right">
                <button type="submit" class="button button-outline-primary button-small">Save</button>
                <button class="button button-primary button-small text-uppercase hidden">Connect</button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
    @if ($errors->any())
        <div  class="alert alert-danger" id="formError">
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
            <div class="col-md-9">
                <div class="input-form-group">
                    <label for="con_name">Connection Name</label>
                    {{ Form::text('con_name', null, ['placeholder' => 'Connection Name', 'class' => 'input', 'id' => 'con_name']) }}
                </div>
            </div>
            <div class="col-md-3">
                <label for="con_active" class="checkbox-ios">
                    {{ Form::checkbox('con_active', null, ['class' => 'input', 'id' => 'con_active']) }}
                    <span>Active</span>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-form-group">
                    <label for="connectionType">Connection Type</label>
                    <div class="select">
                        {!! Form::select('con_type', $connectiondd, null , array('class' => 'form-control', 'id' => 'connectionType')) !!}
                    </div>
                </div>
            </div>
        </div>
        <div id="templateContainer">
            <!-- gets populated from JS -->
        </div>
    </div>
    {{ Form::close() }}
</div>

@stop

@section('scripts')
<template id="wordpressTemplate">
    <div class="row" >
        <div class="col-md-12">
            <div class="input-form-group">
                <label for="api_url">Wordpress URL</label>
                {{ Form::text('api[url]', null, ['placeholder' => 'Wordpress URL', 'class' => 'input', 'id' => 'api_url']) }}
                <p class="help-block">wordpressdomain.com</p>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <button
                type='submit'
                class="btn btn-primary">
                Connect to WordPress
            </button>
        </div>
    </div>
</template>

<template id="facebookTemplate">
    <div class="row" >
        <div class="col-md-12">
            <button
                type='submit'
                class="btn btn-primary">
                Connect to Facebook
            </button>
        </div>
    </div>
</template>

<template id="twitterTemplate">
    <div class="row" >
        <div class="col-md-12">
            <button
                type='submit'
                class="btn btn-primary">
                Connect to Twitter
            </button>
        </div>
    </div>
</template>

<template id="hubspotTemplate">
    <div class="row" >
        <div class="col-md-12">
            <button
                    type='submit'
                    class="btn btn-primary">
                Connect to HubSpot
            </button>
        </div>
    </div>
</template>

<template id="mailchimpTemplate">
    <div class="row" >
        <div class="col-md-12">
            <button
                    type='submit'
                    class="btn btn-primary">
                Connect to Mailchimp
            </button>
        </div>
    </div>
</template>

<template id="linkedinTemplate">
    <div class="row" >
        <div class="col-md-12">
            <button
                    type='submit'
                    class="btn btn-primary">
                Connect to LinkedIn
            </button>
        </div>
    </div>
</template>

<script type="text/javascript">
$(function() {

    var templateName;

    if ($("#connectionType").val() != "") {
        templateName = '#' + $('#connectionType').val() + 'Template';
        $("#templateContainer").html($(templateName).html());
    }

    // because each API will have its own data it needs we need to be able to swap out the form fields
    $("#connectionType").on('change', function() {
        var value = $(this).val();

        // ensure we have a value
        if (value) {
            // lets load the template into the container
            templateName = '#' + value + 'Template';
            $("#templateContainer").html($(templateName).html());
        }
    });

    // # -- If we have form error lets open the panel again
    // # -- Must be below other code
    if ($('#formError').length > 0) {
        $("#newConnectionButton").click();
    }


    $(function(){
        //tasks
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }
    });
});
</script>
@stop