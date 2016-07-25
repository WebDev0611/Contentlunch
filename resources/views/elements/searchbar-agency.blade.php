<header class="search-bar">
    <div class="row">
      <div class="col-md-3">
        <div class="header-clients">
          <div class="dropdown-client">
            <a href="#" class="drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="company-logo"><img src="/images/logo-client-fake.jpg" alt="XX"></span>
              Company Name
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#"><span class="company-logo"><img src="/images/logo-client-fake.jpg" alt="XX"></span>Company 2</a></li>
              <li><a href="#"><span class="company-logo"><img src="/images/logo-client-fake2.jpg" alt="XX"></span>Company 3</a></li>
              <li><a href="#"><span class="company-logo"><img src="/images/logo-client-fake2.jpg" alt="XX"></span>Company 4</a></li>
            </ul>
            <a href="#" class="btn-addclient" data-toggle="modal" data-target="#modal-invite-client"><i class="icon-add"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-5">
            <input type="text" class="search-bar-input" placeholder="Search anything (content, user, rating...)">
        </div>
        <div class="col-md-4 text-right">
          <div class="search-bar-actions">
            <button class="search-bar-button-primary btn-create">
                Create
                <span class="caret"></span>
            </button>
            <button class="search-bar-button">
                <i class="icon-checklist"></i>
            </button>
            <button class="search-bar-button">
                <i class="icon-users"></i>
            </button>
            <button class="search-bar-button">
                <i class="icon-chat"></i>
            </button>
            <button class="search-bar-button" data-toggle="modal" data-target="#modal-chat">
                <i class="icon-agency-chat"></i>
            </button>
          </div>
        </div>
    </div>
</header>


<!-- Modal -->
<div class="modal fade" id="modal-invite-client" tabindex="-1" role="dialog" aria-labelledby="Invite Client">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Invite Client</h4>
      </div>
      <div class="modal-body">
        
        <div class="inner">
          <p class="intro">Invite client to ContentLaunch to share content, plans and more.</p>
          
          <div class="input-form-group">
            <label for="#">Invite</label>
            <input type="text" class="input" placeholder="One or more e-mail addresses">
          </div>
          
          <div class="input-form-group tight">
            <label for="#">Allow access to</label>
            <div class="row">
              <div class="col-sm-6">
                <div class="list-checks">
                  <label for="access1" class="checkbox-primary">
                    <input id="access1" type="checkbox">
                    <span>Ideas</span>
                  </label>
                  <label for="access2" class="checkbox-primary">
                    <input id="access2" type="checkbox">
                    <span>Content</span>
                  </label>
                  <label for="access3" class="checkbox-primary">
                    <input id="access3" type="checkbox">
                    <span>Calendar</span>
                  </label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="list-checks">
                  <label for="access1" class="checkbox-primary">
                    <input id="access1" type="checkbox">
                    <span>Ideas</span>
                  </label>
                  <label for="access2" class="checkbox-primary">
                    <input id="access2" type="checkbox">
                    <span>Content</span>
                  </label>
                  <label for="access3" class="checkbox-primary">
                    <input id="access3" type="checkbox">
                    <span>Calendar</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          
          
          <button class="button button-extend text-uppercase">
              Send Invitation
          </button>
          
          
        </div>
      </div>
    </div>
  </div>
</div>

