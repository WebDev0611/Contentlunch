<div id="shareTrendModal" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-8">
                <h4 class="sidemodal-header-title large">Share Article</h4>
                <div id="trend-share-alert" class="alert collapse alert-danger alert-dismissable">
                    <button type="button" class="close" onclick="$('#trend-share-alert').slideUp()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div id="trend-status-text"></div>
                </div>
            </div>
            <div class="col-md-4 text-right" id="idea-menu">
                <button class="button button-primary button-small text-uppercase share-trend" disabled>
                    <i class="icon-share icon-vertically-middle"></i>&nbsp;Share
                </button>

                <img id="trend-share-loading" src="/images/loading.gif" style="display: none; max-height:30px;" />

                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="input-form-group">
            <label for="connectionType">CHOOSE CONNECTION</label>
            <div class="select">
                <select class="form-control" id="connectionType" name="con_type">
                </select>
            </div>
        </div>

        <div class="input-form-group">
            <label for="#">POST TEXT <span class="character-limit-label hide"> (140 Character or less)</span></label>
            <textarea rows="4" class="input post-text" placeholder="What would you like to say about this article?"></textarea>
        </div>
       <!-- <div class="input-form-group hide">
            <label for="#">HASH TAGS</label>
            <input type="text" class="input hash-tags" placeholder="Enter comma separated hash tags.">
        </div>-->

        <div class="form-delimiter">
            <span>
                <em>Selected Content</em>
            </span>
        </div>
        <div id="selected-content">
        </div>

    </div>
</div>

<div id="trendShareCompleted" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ARTICLE SHARED</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <i class="modal-icon-success icon-check-large"></i>
                        <div class="form-group">
                            <img src="/images/cl-avatar2.png" alt="#" class="create-image">
                            <h4 class="article-title"></h4>
                        </div>
                        <p class="text-gray">IS NOW PUBLISHED TO:</p>
                        <div class="modal-social">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <a href="{{ route('contentIndex') }}" class="button text-uppercase button-extend">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
