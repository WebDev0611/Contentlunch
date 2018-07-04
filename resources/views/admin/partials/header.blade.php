<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $title }}</h2>
        {{-- Let's add support to the $loop variable when we upgrade to 5.4 --}}
        <ol class="breadcrumb">
            <li class="{{ @isset($isHome) ? 'active' : '' }}">
                <a href="/admin">Home</a>
            </li>
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="active">
                    <a href="{{ $breadcrumb['url'] }}">
                        {{ $breadcrumb['name'] }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
</div>