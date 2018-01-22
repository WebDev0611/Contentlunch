@extends('layouts.master')

@section('content')
    <div class="workspace workspace-review">
        <!-- Pannel Container -->
        <div class="panel clearfix">
            <!-- Main Pane -->
            <div class="panel-main">
                <!-- Panel Container -->
                <div class="panel-container">
                    <div class="inner">
                        <article class="article article-review">
                            <header class="article-header">
                                <h1 id="content-title">{{$content->title}}</h1>
                                <p class="article-date">Potter Co.  <span class="article-spacer">·</span>
{{ \Carbon\Carbon::parse($content->created_at)->format('d/m/Y')}}</p>
                            </header>
                            <div class="article-body">
                                <i class="icon-article article-picto"></i>
                                <p class="article-heading">Article</p>
                                <div class="article-block">
                                    {!! $content->body !!}
                                </div>
                            </div>
                            <div class="article-tags">
                                <i class="icon-tags article-picto"></i>
                                <p class="article-heading">Tags</p>
                                <ul class="list-inline list-tags">
                                    @foreach ($content->tags as $tag)
                                    <li>{{ $tag->tag }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="article-images">
                                <i class="icon-content article-picto"></i>
                                <p class="article-heading">Images</p>
                                <ul class="list-unstyled list-images">
                                    @foreach ($images as $image)
                                    <li>
                                        <a href="{{ $image->filename }}" target="_blank">
                                            <img src="{{ $image->filename }}" />
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <!-- Side Pane -->
            <aside class="panel-sidebar">
                <div class="sidetab-reviewinfo">
                    <dl class="list-unstyled">
                        <dt>Shared by</dt>
                        <dd>
                            <div class="user-avatar">
                                <img src="/images/avatar.jpg" alt="#">
                            </div>
                            Jenny Hurley
                        </dd>
                    </dl>
                    <dl class="list-unstyled">
                        <dt>Content Type</dt>
                        <dd>
                            <p class="article-type article-type-blog"><span class="circle"><i class="icon-type-blog"></i></span>Blog post</p>
                        </dd>
                    </dl>
                    <dl class="list-unstyled">
                        <dt>To be published</dt>
                        <dd><span class="badge">In {{ $content->dueInDays }} days</span> ({{$content->present()->dueDateFormat}})</dd>
                    </dl>
                </div>
                <div class="review-controls">
                    <button class="button-withlasticon button-green btn-approvecontent" data-toggle="modal" data-target="#modal-approve">
                        Approve
                        <span class="icon icon-check-light"></span>
                    </button>
                    <button class="button-withlasticon button-red btn-rejectcontent" data-toggle="modal" data-target="#modal-reject">
                        Needs Edits
                        <span class="icon icon-x"></span>
                    </button>
                </div>
                <div class="panel-header">
                    <ul class="panel-tabs withborder">
                        <li class="active">
                            <a href="#sidetab-comments" role="tab" data-toggle="tab">Comments</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <!-- Content Task Panel -->
                    <div role="tabpanel" class="sidepanel tab-pane active" id="sidetab-comments">
                        <content-feedback-comment-list></content-feedback-comment-list>
                    </div>
                </div>
            </aside>
        </div>
    </div>


    <guests-invite-modal
        content-id="{{ $content->id }}"
        type='content'>
    </guests-invite-modal>

    @include('content.partials.editor.launch_modals')


    @include('content.partials.review.approve_modal')
    @include('content.partials.review.reject_modal')
    @include('content.partials.review.complete_approve_modal')
    @include('content.partials.review.complete_reject_modal')
    @include('content.partials.review.welcome_modal')
@stop

@section('scripts')
<script>
    // Welcome 
    $('#modal-welcome').modal('show');
    $('#start-tour').on('click', function(){
      tour.start();
    });

    // Tour 
    var tour = new Shepherd.Tour({
        defaults: {
            classes: 'shepherd-theme-arrows'
        }
    });

    tour.addStep('editor', {
        title: '1/5: This is your main editor area',
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla arcu nisl, eleifend id justo sed, feugiat dignissim justo.',
        attachTo: '.article-review right',
        buttons: [
            {
                text: 'Next Step',
                classes: 'text-uppercase button button-small',
                action: tour.next
            },
            {
                text: 'Quit',
                classes: 'text-uppercase button button-outline-secondary button-small',
                action: tour.cancel
            }
        ],
        advanceOn: '.docs-link click'
    });
</script>
@stop
