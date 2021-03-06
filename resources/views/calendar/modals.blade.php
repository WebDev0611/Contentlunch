<!-- Sidemodal: Add Idea -->
<div id="addIdeaCalendar" class="sidemodal large">
  <div class="sidemodal-header">
    <div class="row">
      <div class="col-md-6">
        <h4 class="sidemodal-header-title large">Add idea to calendar</h4>
        <p>Please select idea you want to add</p>
      </div>
      <div class="col-md-6 text-right">
        <!--
        <button class="button button-primary button-small text-uppercase">Add Idea</button>
         -->
        <button class="sidemodal-close normal-flow" data-dismiss="modal">
          <i class="icon-remove"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="sidemodal-container nopadding">
    <!--
    <div class="sidemodal-search">
      <input type="text" class="onboarding-connect-section-input" placeholder="Quick search">
    </div>
  -->
    <div class="sidemodal-scollable withsearch">

      <ul class="list-unstyled list-content" id="calendar-idea-list">
      </ul>
    </div>


  </div>
</div>

<script type="text/template" id="calendar-idea-template">
      <div class="list-avatar">
        <div class="user-avatar">
          <img src="/images/cl-avatar2.png">
        </div>
      </div>
          <div class="list-title">
            <p><a href="#"><%= name %></a></p>
          </div>
          <div class="list-datestamp">
            <p><%= updated_at %></p>
          </div>
</script>

<!-- End Sidemodal: Add Idea -->


<!-- Sidemodal: Add Content -->
<div id="addContentCalendar" class="sidemodal large">
  <div class="sidemodal-header">
    <div class="row">
      <div class="col-md-6">
        <h4 class="sidemodal-header-title large">Add content to calendar</h4>
        <p>Please select content you want to add</p>
      </div>
      <div class="col-md-6 text-right">
        <button class="sidemodal-close normal-flow" data-dismiss="modal">
          <i class="icon-remove"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="sidemodal-container nopadding">
    <!--
    <div class="sidemodal-search">
      <input type="text" class="onboarding-connect-section-input" placeholder="Quick search">
    </div>
  -->
    <div class="sidemodal-scollable withsearch">

      <ul class="list-unstyled list-content" id="calendar-content-list">

<!--
        <li>
          <div class="list-avatar">
            <div class="user-avatar">
              <img src="/images/cl-avatar2.png">
            </div>
          </div>
          <div class="list-title">
            <p><a href="#">Fintech Industry overview</a></p>
          </div>
          <div class="list-datestamp">
            <p>05/05/2016</p>
          </div>
          <div class="list-type">
            <i class="icon-type-blog"></i>
          </div>
        </li>
-->

      </ul>
    </div>


  </div>
</div>  <!-- End Sidemodal: Add content -->

<script type="text/template" id="calendar-content-template">
      <div class="list-avatar">
        <div class="user-avatar">
          <img src="/images/cl-avatar2.png">
        </div>
      </div>
      <div class="list-title">
        <p><a href="#"><%= title %></a></p>
      </div>
      <div class="list-datestamp">
        <p><%= updated_at %></p>
      </div>
      <div class="list-type">
        <i class="icon-type-facebook"></i>
      </div>
</script>

