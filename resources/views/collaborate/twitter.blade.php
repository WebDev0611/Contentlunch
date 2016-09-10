@extends('layouts.master')

@section('content')
<div class="workspace">

  <!-- Panel -->
  <div class="panel">

    <!-- Panel Header -->
    <div class="panel-header">
      <ul class="panel-tabs withborder text-center">
        <li>
          <a href="/collaborate">Search for Influencers</a>
        </li>
        <li>
          <a href="/collaborate/linkedin">Search LinkedIn Connections</a>
        </li>
        <li class="active">
          <a href="javscript:;">Search Twitter</a>
        </li>
        <li>
          <a href="/collaborate/bookmarks">Bookmarked Influencers</a>
        </li>
      </ul>
    </div> <!-- End Panel Header -->

    <!-- Panel Content -->
    <div class="panel-container bottompadded">

      <!-- Search bar -->
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="form-group">
            <div class="input-form-button prefixed">
              <i class="icon-magnifier picto"></i>
              <input type="text"
                id='twitterSearchField'
                placeholder="Search influencers to work on projects..."
                class="input-search-icon">

              <span class="input-form-button-action">
                <button id='twitterSearchButton' class="button">SEARCH</button>
              </span>
            </div>
          </div>
        </div>
      </div> <!-- End Search bar -->

      <div class="panel-separator">
        <div class="panel-contenthead withfilter">
          <div class="results">
            <p>115 persons found - select person you want to invite to work on project</p>
          </div>
          <div class="filter">
            <div class="row">
              <div class="col-md-3 text-right">
                <label class="select-horizontal-label">Sort by:</label>
              </div>
              <div class="col-md-8">
                <div class="select select-small extend">
                  <select name="#" id="#">
                    <option value="#">Followers</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Influencers list -->
      <div class="inner wide">
        <ul class="list-inline list-influencers" id='twitterUserList'>
        </ul>
      </div><!-- End Influencers list -->

      <a href="#" class="button button-outline-secondary button-extend btn-showmore">SHOW MORE</a>

    </div> <!-- End Panel Content -->

  </div> <!-- End Panel -->


  <!-- Modal: Details -->
  <div id="modal-influencerdetails" class="sidemodal inset" style="display: none;">
    <div class="sidemodal-header">
      <h4 class="sidemodal-header-title">About</h4>
      <button class="sidemodal-close" data-dismiss="modal">
        <i class="icon-remove"></i>
      </button>
    </div>
    <div class="sidemodal-container">

      <!-- Influencer Info -->
      <div class="influencer-info">

        <div class="influencer-head">
          <div class="influencer-pic">
            <div class="user-avatar"><img src="/images/avatar-new.jpg" alt="Influencer Name"></div>
          </div>
          <div class="influencer-data">
            <p class="title">Influencer name</p>
            <p class="desc">Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor lectus nec vestibulum.</p>
          </div>
        </div>

        <ul class="list-inline list-soc">
          <li><i class="icon-twitter2"></i>3,300</li>
          <li><i class="icon-facebook-mini"></i>2,503</li>
        </ul>

        <div class="influenceer-action">
          <div class="btn-group">
            <button type="button" class="button button-default button-extend">INVITE</button>
            <button type="button" class="button button-default button-extend">DETAILS</button>
          </div>

          <button type="button" class="button button-outline-secondary button-extend"><i class="icon-star-outline"></i>BOOKMARK</button>
        </div>

        <div class="influencer-desc">
          <p>In non lobortis sem, in posuere eros. Vivamus commodo erat sit amet maximus rhoncus. Vestibulum nec quam id dui maximus auctor. Etiam sodales accumsan dignissim. Quisque placerat massa nec dictum dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus venenatis ipsum consequat erat interdum ornare. Sed nec bibendum ex, id mollis augue. In non sagittis metus, congue vulputate quam. Etiam fermentum semper metus, vitae commodo sapien dapibus non.</p>
          <p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Maecenas pharetra arcu eu urna dapibus sodales. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent sed lorem quis est tempor sollicitudin. Sed sed lacinia nisi. Vivamus eget malesuada lectus. Fusce rhoncus massa et consequat lacinia. Nulla dapibus vitae diam ut maximus. Mauris vitae tempus elit. Curabitur auctor dui sit amet odio luctus, eu accumsan purus bibendum. Etiam porta nisi at velit hendrerit tincidunt. Nulla et pellentesque diam.</p>
        </div>

      </div> <!-- End Influencer Info -->

    </div>
  </div> <!-- End Modal: Details -->


  <!-- Modal: Invite Influencer -->
  <div id="modal-inviteinfluencer" class="sidemodal large" style="display: none">

    <div class="sidemodal-header">
      <div class="row">
        <div class="col-md-6">
          <h4 class="sidemodal-header-title large">Invite influencer</h4>
        </div>
        <div class="col-md-6 text-right">
          <button class="button button-primary button-small text-uppercase">Send Invitation</button>
          <button class="sidemodal-close normal-flow" data-dismiss="modal">
            <i class="icon-remove"></i>
          </button>
        </div>
      </div>
    </div>


    <div class="sidemodal-container">

      <div class="influencer-head smaller">
        <div class="influencer-pic">
          <div class="user-avatar"><img src="/images/avatar-new.jpg" alt="Influencer Name"></div>
        </div>
        <div class="influencer-data">
          <p class="title">Influencer name</p>
          <p class="desc">Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor lectus nec vestibulum.</p>
        </div>
      </div>

      <div class="input-form-group">
        <label for="#">Invitation to partner with us</label>
        <select name="" class="input">
          <option disabled selected>Select idea, concept or campaign</option>
          <option>Idea 1</option>
          <option>Idea 2</option>
          <option>Idea 3</option>
        </select>
      </div>

      <div class="input-form-group">
        <label for="#">Content project details</label>
        <textarea rows="1" class="input input-area" placeholder="Explain task"></textarea>
      </div>

      <div class="input-form-group">
        <label for="#">What can we offer you</label>
        <textarea rows="1" class="input input-area" placeholder="Explain your offer"></textarea>
      </div>

      <div class="input-form-group">
        <label for="#">Reference URL</label>
        <input type="text" class="input" placeholder="Paste URL">
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="input-form-group">
            <label for="#">Starts</label>
            <input type="text" class="input input-calendar" placeholder="Select Date">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-form-group">
            <label for="#">Due</label>
            <input type="text" class="input input-calendar" placeholder="Select Date">
          </div>
        </div>
      </div>

      <div class="fileupload">
        <i class="icon-add-content picto"></i>
        <p class="msgtitle">Click to attach one or more documents</p>
        <input type="file" class="input input-upload">
      </div>

    </div>
  </div> <!-- End Modal: Invite Influencer -->
</div>

<script type="text/template" id="twitterUserTemplate">
    <a href="#" class="btn btn-fav"><i class="icon-star-outline"></i><i class="icon-star"></i></a>
    <div class="body">
        <div class="user-avatar">
            <img src="<%= profile_image_url %>" alt="Influencer Name"/>
        </div>
        <p class="title"><%= name %> (@<%= screen_name %>)</p>
        <p class="desc"><%= description %></p>
    </div>
    <div class="foot">
        <ul class="list-inline list-soc">
            <li><i class="icon-linkedin"></i>3,300</li>
            <li><i class="icon-facebook-mini"></i>2,503</li>
        </ul>
        <div class="btn-group">
            <button type="button" class="button button-default" data-toggle="modal" data-target="#modal-inviteinfluencer">INVITE</button>
            <button type="button" class="button button-outline-secondary" data-toggle="modal" data-target="#modal-influencerdetails">DETAILS</button>
        </div>
    </div>
</script>
@stop

@section('scripts')
<script src="/js/twitter.js"></script>
@stop