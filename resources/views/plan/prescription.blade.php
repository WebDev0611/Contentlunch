@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel">
        <div class="panel-header">
            <ul class="panel-tabs text-center">
                <li >
                    <a href="/plan">Topic Generator</a>
                </li>
                <li>
                    <a href="/plan/trends">Content Trends</a>
                </li>
                <li class="active">
                    <a href="/plan/prescription">Content Prescription</a>
                </li>
                <li>
                    <a href="/plan/ideas">Ideas</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h5 class="heading-border text-center">
                    Please fill in this form and we’ll suggest a we’ll suggest a content program
                    <i class="popover-icon icon-question"
                       data-toggle="popover"
                       title="Popover title"
                       data-content="And here's some amazing content. It's very engaging. Right?"
                       data-placement="bottom"
                    ></i>
                </h5>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-form-group">
                                    <label for="#">WHAT ARE YOUR GOALS?</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Select Goal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-form-group">
                                    <label for="#">WHAT IS YOUR MONTHLY BUDGET</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Select amount range</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">COMPANY TYPE</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Local</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="B2B" class="radio-secondary">
                                    <input id="B2B" type="radio">
                                    <span>B2B</span>
                                </label>
                                <label for="B2C" class="radio-secondary">
                                    <input id="B2C" type="radio">
                                    <span>B2C</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-form-group">
                            <label for="#">URL</label>
                            <input type="text" class="input" placeholder="Enter company name">
                        </div>
                        <div class="input-form-group">
                            <label for="#">Paste URL</label>
                            <div class="select">
                                <select name="" id="">
                                    <option value="#">Please select</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-form-group text-right">
                            <button class="button button-primary">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop