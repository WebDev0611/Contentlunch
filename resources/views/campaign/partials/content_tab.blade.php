<div class="content-panel">
    <div class="create-panel-container">
        <h4 class="create-panel-heading">
            <i class="icon-alert"></i>
            READY TO BE PUBLISHED
        </h4>

        @forelse ($readyToPublishContent as $content)
            @include('campaign.partials.content_tab.ready_content_loop')
        @empty
            <div class="alert alert-info alert-forms" role="alert">
                <p>
                    No Content that is ready for publishing for this campaign.
                </p>
            </div>
        @endforelse
   </div>
</div>

<!-- Panel Being Edited / Written -->
<div class="content-panel">
     <div class="create-panel-container">
         <h4 class="create-panel-heading">
            <i class="icon-edit"></i>
            BEING EDITED / WRITTEN
         </h4>

         @forelse ($beingWrittenContent as $content)
             @include('campaign.partials.content_tab.written_content_loop')
         @empty
             <div class="alert alert-info alert-forms" role="alert">
                <p>
                    No content being written for this campaign.
                </p>
             </div>
         @endforelse
    </div>
</div>