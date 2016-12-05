@forelse ($contents as $content)
    <div class="search-item">

        <h5 class="dashboard-tasks-title">
            {{ $content->title }}
        </h5>
        <span class="dashboard-tasks-text">
            Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
        </span>
        <ul class="dashboard-tasks-list">
            <li>DUE IN: <strong>2 DAYS</strong></li>
            <li>
                <a href="#"><strong>Edit Content</strong></a>
            </li>
        </ul>
    </div>
@empty
    <div class="alert alert-info alert-forms">
        No content found with the search term <strong>{{ $searchTerm }}</strong>
    </div>
@endforelse