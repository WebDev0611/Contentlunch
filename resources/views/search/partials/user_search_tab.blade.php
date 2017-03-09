@forelse ($users as $user)
    <div class="search-item">
        <div class="row">
            <div class="col-sm-1">
                <img src="{{ $user->present()->profile_image }}" alt="#" class="search-item-img">
            </div>
            <div class="col-sm-11">
                <h5 class="dashboard-tasks-title">
                    {{ $user->name }}
                </h5>
                <span class="dashboard-tasks-text">
                    {{ $user->present()->location }}
                </span>
                <ul class="dashboard-tasks-list">
                    <li>
                        <a href="{{ route('inviteUser', $user) }}">
                            <strong>Invite User to {{ $selectedAccount->name }}</strong>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info alert-forms">
        No users found with the search term <strong>{{ $searchTerm }}</strong>.
    </div>
@endforelse