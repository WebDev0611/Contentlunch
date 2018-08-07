@can('guests-denied')
<div class="input-form-group">
    <label>Publishing Guidelines</label>
    <p class="form-control-static">
        @if (isset($guidelines) && $guidelines->publishing_guidelines)
            {{ $guidelines->publishing_guidelines }}
        @else
            <small>
                No Content Guidelines set. <a href="{{ route('content_settings.index') }}">Click here</a> to configure them.
            </small>
        @endif
    </p>
</div>
@endcan