<div id="shareTrendModal" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Share Article</h4>
                <div id="idea-status-alert" class="alert hidden">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div id="idea-status-text"></div>
                </div>
            </div>
            <div class="col-md-6 text-right" id="idea-menu">
                <button class="button button-primary button-small text-uppercase save-idea">
                    <i class="icon-share icon-vertically-middle"></i>&nbsp;Share</button>
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
                    <option value="" selected="selected">-- Select One --</option>
                    <option value="facebook">Facebook</option>
                    <option value="twitter">Twitter</option>
                    <option value="wordpress">Wordpress</option>
                </select>
            </div>
        </div>

        <div class="input-form-group">
            <label for="#">POST TEXT <span class="character-limit-label hide"> (140 Character or less)</span></label>
            <textarea rows="4" class="input post-text" placeholder="What would you like to say about this article?"></textarea>
        </div>
        <div class="input-form-group hide">
            <label for="#">HASH TAGS</label>
            <input type="text" class="input hash-tags" placeholder="Enter comma separated hash tags.">
        </div>

        <div class="form-delimiter">
            <span>
                <em>Selected Content</em>
            </span>
        </div>
        <div id="selected-content">
        </div>

    </div>
</div>

<script type="text/template" id="selected-trend-template">
    <div class="tombstone tombstone-horizontal tombstone-active clearfix">
        <div class="tombstone-image">
            <img src="<%= image %>" alt="">
        </div>
        <div class="tombstone-container">
            <h3><%= title %></h3>
            <p>
                <%= title %>
            </p>
        </div>
    </div>
</script>

<script type="text/template" id="selected-topic-template">
    <div class="tombstone tombstone-horizontal tombstone-active clearfix">
        <div class="tombstone-container">
            <h3 class="col-md-offset-1"><%= keyword %></h3>
        </div>
    </div>
</script>