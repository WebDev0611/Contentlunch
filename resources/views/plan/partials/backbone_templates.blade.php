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

<script type="text/template" id="trend-result-template">
   <div class="col-md-3">
        <div class="tombstone">

            <button type="button" data-target="#shareTrendModal" data-toggle="modal" class="button button-primary button-small text-uppercase tombstone-share">
                <i class="icon-share icon-vertically-middle"></i>&nbsp;SHARE
            </button>

            <div class="tombstone-image">
                <img src="<%= image %>" alt="">
                <span><%= when %>  ·  <%= source %></span>
            </div>
            <div class="tombstone-container">
                <h3><%= title %></h3>
                <p>
                    <%= author %>
                </p>
            </div>
            <div class="tombstone-social">
                <div class="tombstone-cell">
                    <i class="icon-share"></i>
                    <%= total_shares %>
                </div>
                <!--<div class="tombstone-cell">
                    <i class="icon-facebook-mini"></i>
                    <%= fb_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-twitter2"></i>
                    <%= tw_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-google-plus"></i>
                    <%= google_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-youtube"></i>
                    <%= video %>
                </div>-->
            </div>
        </div>
    </div>
</script>