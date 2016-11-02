@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.profile_sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                <!-- navigation -->
                @include('settings.partials.navigation')
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-10">
                        <div class="settings-import">
                            <div class="settings-import-container">
                                <div class="settings-import-action">
                                    <span>
                                        Facebook Accounts
                                    </span>
                                </div>
                                {{ Form::open(array('url' => 'callback/facebook/account/save')) }}
                                <div class="settings-import-list">
                                    <div class="input-form-group">
                                        <label for="content_type">CONTENT TYPE</label>
                                        {!! Form::select('facebook_account', $accountOptions, '' , array('class' => 'input selectpicker form-control', 'id' => 'contentType', 'data-live-search' => 'true', 'title' => 'Choose Facebook Account')) !!}
                                    </div>
                                </div>

                                <input type="hidden" value="{{$connection_id}}" name="connection_id">
                                <button type="submit" class="button button-outline-primary button-small">
                                    Save
                                </button>
                                {{ Form::close() }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
$(function() {


});
</script>
@stop