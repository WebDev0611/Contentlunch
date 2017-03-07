@extends('layouts.master')


@section('content')
@php
    $disabled = $is_collaborator ? '' : 'disabled="disabled"';
@endphp
<div class="workspace">
    <div class="row">
        <div id="responses" class="col-md-12"></div>
    </div>
    <div class="panel clearfix">
        <div class="panel-main" id="idea-editor">
            <div class="panel-header">
                <div class="panel-options">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Idea editor</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <button {{ $disabled }} type="button" class="button button-outline-secondary button-small save-idea">SAVE</button>
                            <div class="btn-group">
                                <a {{ $disabled }} href="{{ route('ideaWrite', $idea) }}" class="button button-small">WRITE IT</a>
                                <button {{ $disabled }} type="button" class="button button-small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>

                                @if ($is_collaborator)
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#" class="reject-idea">Reject Idea</a></li>
                                    <li><a href="#" class="park-idea">Park Idea</a></li>
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        {{ csrf_field() }}
                        <div class="input-form-group">
                            <label for="#">CONCEPT NAME</label>
                            <input {{ $disabled }}
                                type="text"
                                id="idea-name"
                                class="input"
                                placeholder="Enter your concept name"
                                value="{{ $idea->name}}">
                        </div>
                        <div class="input-form-group">
                            <label for="#">EXPLAIN YOUR IDEA</label>
                            <textarea {{ $disabled }}
                                rows="4"
                                id="idea-text"
                                class="input"
                                placeholder="Explain idea in a paragraph or so">{{ $idea->text }}</textarea>
                        </div>
                        <div class="input-form-group">
                            <label for="#">TAGS</label>
                            <input {{ $disabled }}
                                type="text"
                                id="idea-tags"
                                class="input"
                                placeholder="Enter comma separated tags"
                                value="{{ $idea->tags }}">
                        </div>

                        <div class="form-group">
                            <fieldset class="form-fieldset clearfix">
                                <legend class="form-legend">Collaborators</legend>
                                <ul class="images-list pull-left" id='ideas-collaborator-list'>

                                </ul>

                                @if ($is_collaborator)
                                    <button type="button" id='open-collab-modal' class="button button-action large pull-right">
                                        <i class="icon-add-circle"></i>
                                    </button>
                                @endif
                            </fieldset>
                        </div>

                        {{--
                        <div class="form-group">
                            @include('plan.partials.invite_guests')
                        </div>
                        --}}

                        <div class="form-delimiter">
                            <span>
                                <em>Selected Content</em>
                            </span>
                        </div>

                        @forelse ($contents as $content)
                            <div class="tombstone tombstone-horizontal tombstone-active clearfix">
                                <div class="tombstone-image">
                                    <img src="{{$content->image}}" alt="">
                                </div>
                                <div class="tombstone-container">
                                    <h3>{{$content->title}}</h3>
                                    <p>
                                        {{$content->link}}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="form-group">
                                <div class="alert alert-info alert-forms">No content attached yet</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('plan.partials.editor_sidebar') --}}
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
var idea_obj = {!! $idea !!};
</script>
<script src="/js/idea_editor.js"></script>
@stop