@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel clearfix">
        {!!
            Form::model($user, [
                    'url' => route('settingsUpdate'),
                    'files' => 'true'
                ])
        !!}
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
                @if ($errors->any())
                    <div class="alert alert-danger" id="formError">
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
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-form-group text-right">
                            <label for="#" class="checkbox-ios">
                                <span>Account Active</span>
                                <input type="checkbox">
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">FULL NAME</label>
                                    {!!
                                        Form::text(
                                            'name',
                                            @isset($user) ? $user->name : '',
                                            [
                                                'class' => 'input',
                                                'placeholder' => 'Name Surname'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">ACCOUNT NAME</label>
                                    <input type="text" class="input" placeholder="Account name">
                                </div>
                            </div>
                        </div>
                        <div class="input-form-group">
                            <label for="#">ADDRESS</label>
                            <input type="text" class="input" placeholder="Account holder address">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">CITY</label>
                                    <input type="text" class="input" placeholder="Houston">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">COUNTRY</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">USA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-delimiter">
                            <span>
                                <em>Contacts</em>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">PHONE NUMBER</label>
                                    <input type="text" class="input" placeholder="+1 212 123455">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">EMAIL ADDRESS</label>
                                    {!!
                                        Form::text(
                                            'email',
                                            @isset($user) ? $user->email : '',
                                            [
                                                'class' => 'input',
                                                'placeholder' => 'Your email here'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                        </div>
                        <div class="input-form-group">
                            <button type='submit' class="button button-extend">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@stop