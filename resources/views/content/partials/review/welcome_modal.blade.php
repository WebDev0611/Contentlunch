<div class="modal fade" id="modal-welcome" tabindex="-1" role="dialog" aria-labelledby="Review Content" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">REVIEW CONTENT</h4>
      </div>
      <div class="modal-body">
        <div class="inner text-center">
          <img class="modal-picto" src="{{ asset('./images/picto-review-content.png') }}" alt="Review Content"/>
          <p class="medium">Greetings. You are about to review <strong>{{ $content->title }}</strong> document shared with you by <strong>{{ $account->name }}</strong>. Let us give you a quick user interface tour before you begin your review.</p>
        </div>
      </div>
      <div class="modal-footer centered widebuttons">
        <button type="button" class="button button-outline-secondary" data-dismiss="modal">SKIP</button>
        <button type="button" class="button button-default" data-dismiss="modal" id="start-tour">START TOUR</button>
      </div>
    </div>
  </div>
</div>