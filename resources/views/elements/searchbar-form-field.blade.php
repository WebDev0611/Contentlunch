{{ Form::open([ 'id'=>'searchForm', 'url' => '/search' ]) }}
    {{
        Form::text('search', null, [
            'placeholder' => 'Search anything (content, user, rating... )',
            'class' => 'search-bar-input'
        ])
    }}
{{ Form::close() }}