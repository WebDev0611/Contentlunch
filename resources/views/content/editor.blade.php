@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->
            <div class="panel-main">
                @if (isset($content))
                    {!! Form::model($content, ['url' => route('content.update', $content), 'files' => 'true' ]) !!}
                @else
                    {{ Form::open(['url' => 'edit', 'files'=>'true']) }}
                @endif
                {{ Form::hidden('content_id', $content->id) }}

                @include('content.partials.editor.header')

                @include('content.partials.editor.main_form')

                {{ Form::close() }}
            </div>

            <!-- Side Pane -->
            <aside class="panel-sidebar" id='editor-panel-sidebar'>
                @include('content.partials.editor.sidebar')
            </aside>

        </div>
    </div>

    <guests-invite-modal
        content-id="{{ $content->id }}"
        type='content'>
    </guests-invite-modal>

    @include('content.partials.editor.launch_modals')
@stop

@section('scripts')
<script>
    var TWEET_CONTENT_TYPE = {!! App\ContentType::whereName('Tweet')->first()->id !!};
    var connections_details = {!! $connectionsDetails !!};
    var mailchimp_settings = {!! empty($content->mailchimp_settings) ? '""' : $content->mailchimp_settings !!};
</script>
<script type='text/javascript'>
    (function() {

        var source = {!! $tagsJson !!} || [];
        var contentTags = {!! $contentTagsJson !!};

        $('#tag-editor').tagEditor({
            placeholder: 'Select tags...',
            initialTags: contentTags,
            autocomplete: {
                source: source,
                minLength: 1,
            },
        });
    })();
</script>
<script src="/js/content.js"></script>
@stop
