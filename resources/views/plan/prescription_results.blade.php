@extends('layouts.master')


@section('content')
    <div class="workspace">
        <div class="panel">
            <div class="panel-header">
                <ul class="panel-tabs text-center">
                    <li>
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

            <div class="row prescription-results">

                <div class="col-md-8 col-md-offset-2">

                    <div class="row">
                        <div class="col-md-12 text-center">

                            <p class="result-heading">
                                Thanks for completing the form! <br> The Content Launch Team believes this would be the best Content Prescription for your company:
                            </p>
                            <h3>{{$contentPackage}}</h3>

                            <p>The Content Launch Writing Team would be happy to help write this content for you. Ready to get started?</p>

                            <a href="{{route('contents.create') . '#GetContentWritten'}}">
                                <button class="btn btn-primary btn-lg">Get Content Written</button>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop