@can('guests-denied')
@if (isset($guidelines) && $guidelines->publishing_guidelines && $guidelines->company_strategy)
<div class="row">
    <div class="col-sm-6">
        <div class="input-form-group">
            <label>Publishing Guidelines</label>
            <p class="form-control-static">
                {{ $guidelines->publishing_guidelines }}
            </p>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="input-form-group">
            <label>Content Strategy</label>
            <p class="form-control-static">
                {{ $guidelines->company_strategy }}
            </p>
        </div>
    </div>
</div>
@endif
@endcan