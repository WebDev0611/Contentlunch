<div class="content-panel">
    <div class="create-panel-container">
        <h4 class="create-panel-heading">
            <i class="icon-alert"></i>
            READY TO BE PUBLISHED
        </h4>

        @forelse ($contents as $content)
            @include('campaign.partials.content_tab.ready_content_loop')
        @empty
            <div class="alert alert-info alert-forms" role="alert">
                <p>
                    No Content that is ready for publishing at this moment.
                </p>
            </div>
        @endforelse
   </div>
</div>

<!-- Panel Being Edited / Written -->
<div class="content-panel">
    <p class="panel-head"><span class="picto"><i class="icon-edit"></i></span> BEING EDITED / WRITTEN</p>
    <ul class="list-unstyled list-content bordered">
        <li class="list-external">
            <div class="progressbar" style="width: 35%"></div>
            <div class="list-avatar">
                <div class="user-avatar">
                    <img src="/images/cl-avatar2.png">
                </div>
            </div>
            <div class="list-title">
                <a href="#">
                    <p>Write blog post on online banking <span class="badge-external">External</span></p>
                    <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>EDIT</strong></p>
                </a>
            </div>
            <div class="list-team">
            </div>
            <div class="list-type">
                <i class="icon-type-blog"></i>
            </div>
        </li>
        <li>
            <div class="progressbar" style="width: 85%"></div>
            <div class="list-avatar">
                <div class="user-avatar">
                    <img src="/images/cl-avatar2.png">
                </div>
            </div>
            <div class="list-title">
                <a href="#">
                    <p>16 social postings on woman rights and movements around the world</p>
                    <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>EDIT</strong></p>
                </a>
            </div>
            <div class="list-type">
                <i class="icon-type-facebook"></i>
            </div>
        </li>
    </ul>
</div>