@extends('layouts.minimal')

@section('content')

    <div class="landing">
        <a href="#" class="landing-logo">
            <img src="/images/logo-full.svg" alt="#">
        </a>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="onboarding-container">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <h5 class="onboarding-step-text text-center">
                                    Invite team members with whom you will collaborate
                                </h5>
                                <h5 class="onboarding-text text-center">
                                    Connect as many services you use. Based on informationfrom your social and publishing
                                    platforms we calculate your CL score
                                </h5>
                                <div class="onboarding-step">
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="onboarding-import">
                                    <ul class="onboarding-import-menu">
                                        <li>
                                            <a href="#services" data-toggle="tab">Import Services</a>
                                        </li>
                                        <li class="active">
                                            <a href="#connections" data-toggle="tab">Content Connections</a>
                                        </li>
                                    </ul>
                                    <div class="onboarding-import-container tab-content">
                                        <div id="services" class="onboarding-import-list tab-pane">
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">WordPress</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="connections" class="onboarding-import-list tab-pane active">
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item active">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-connected button-small">Connected</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item active">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-connected button-small">Connected</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                            <div class="onboarding-import-item">
                                                <div class="col-md-6">
                                                    <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                    <span class="onboarding-import-item-title">Joomla</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="button button-small">Connect</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <a href="/#/onboarding/5" class="button button-extend text-uppercase">Next Step</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop




