@foreach ($users as $user)
    <div class="search-item">
        <div class="row">
            <div class="col-sm-1">
                <img src="{{ $user->profile_image ?: '/images/avatar.jpg' }}" alt="#" class="search-item-img">
            </div>
            <div class="col-sm-11">
                <h5 class="dashboard-tasks-title">
                    {{ $user->name }}
                </h5>
                <span class="dashboard-tasks-text">
                    Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                </span>
                <ul class="dashboard-tasks-list">
                    <li>
                        <a href="#"><strong>Invite User</strong></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endforeach