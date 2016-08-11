@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <aside class="panel-sidebar right-separator">
            <div class="panel-container text-center">
                <div class="settings-profile-image">
                    <img src="/assets/images/avatar.jpg" alt="#">
                </div>
                <div class="settings-profile-info">
                    <h4>Storm Trooper</h4>
                    <span>New York, USA</span>
                </div>
                <span class="settings-profile-subscription">Paid Subscription</span>
                <label for="#">Paid Monthly</label>
                <h3 class="settings-profile-heading">$700</h3>

                <label for="#">Max Users</label>
                <h3 class="settings-profile-heading">$700</h3>

                <div class="form-group">
                    <a href="#" class="text-blue text-uppercase">
                        Upgrade Subscription
                    </a>
                </div>
                <div class="form-group">
                    <label for="#">Payment Info</label>
                    <span>
                        VISA X-1203
                        <a href="#" class="text-blue text-uppercase">
                            <i class="icon-edit"></i>
                            Edit
                        </a>
                    </span>
                </div>
                <div class="form-group">
                    <label for="AutoRenew" class="checkbox-primary text-inline">
                        <input id="AutoRenew" type="checkbox">
                        <span>Auto Renew</span>
                    </label>
                </div>
            </div>
        </aside>
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
                                <div class="settings-import-list">

                             {{ Form::open(array('url' => 'callback/facebook/account/save')) }}
                                <div class="input-form-group">
                                    <label for="content_type">CONTENT TYPE</label>
                                   {!! Form::select('facebook_account', $accountOptions, '' , array('class' => 'input selectpicker form-control', 'id' => 'contentType', 'data-live-search' => 'true', 'title' => 'Choose Facebook Account')) !!}
                                </div>
                            </div>
                                 
                                <input type="hidden" value="{{$connection_id}}" name="connection_id"> 
                <button type="submit" class="button button-outline-primary button-small">Save</button>
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