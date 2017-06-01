@if(count($connections) > 0)
    @foreach($connections as $con)
    <div class="settings-import-item">
        <div class="col-md-6">
            <span class="icon-social icon-social-{{ $con->provider->slug }}"></span>
            <span class="settings-import-item-title">{{ $con->name }}</span>
        </div>
        <div class="col-md-6 text-right">
            @if ($con->active)
                {{ Form::open([ 'url' => route('connections.destroy', $con), 'method' => 'delete' ]) }}
                <button type='submit' class="button button-small">Disconnect</button>
                {{ Form::close() }}
            @else
                <button class="button button-small">Connect</button>
            @endif
        </div>
    </div>
    @endforeach
@else
    <div class="alert alert-info alert-forms" role="alert">
        <p>You have no connections at this time.</p>
    </div>
@endif