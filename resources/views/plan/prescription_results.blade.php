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
                                Based on the input you provided,
                                we selected a Content Package that best suits your needs:
                            </p>
                            <h3>{{$contentPackage}}</h3>

                            <p>Want us to write it for you? Just click the button below.</p>

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