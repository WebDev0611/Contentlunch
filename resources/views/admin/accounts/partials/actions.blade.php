<div class="m-b">
    @php
        $route = Route::getCurrentRoute()->getName();
    @endphp
    @if ($route === 'admin.accounts.edit')
    <a href="{{ route('admin.accounts.update', $account) }}" class="btn btn-primary">
        <i class="fa fa-save"></i>
        Save Account
    </a>
    @endif
    <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-white">
        <i class="fa fa-pencil"></i>
        Edit Account
    </a>
</div>