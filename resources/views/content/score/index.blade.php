@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">
            <div class="panel-header">
                <ul class="panel-tabs spacing">
                    <li class="active panel-tabs-all-content">
                        <a href="#">Content Score Test</a>
                    </li>
                </ul>
            </div>

            <div class="create-panel-container">
                <h4 class="create-panel-heading">
                    {{$data['url']}} <br>
                </h4>

                <div class="alert alert-info alert-forms" role="alert">
                    <p>No Content that is ready for publishing at this moment.</p>
                </div>

            </div>
        </div>
        {{--
        <aside class="panel-sidebar">
            @include('content.partials.dashboard.ideas-sidebar')
        </aside>
        --}}
    </div>
</div>


@stop

@section('scripts')
<script>

</script>
@stop