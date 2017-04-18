@extends('admin.layouts.master')

@section('header')
    @include('admin.partials.header', [
        'title' => 'Accounts',
        'breadcrumbs' => [
            [ 'name' => 'Accounts', 'url' => route('admin.accounts.index') ],
        ]
    ])
@stop

@section('content')
    <div class="row">
        <div class="col-sm-6 form-group">
            <div class="form-inline">
                {{
                    Form::text('search', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Search...',
                    ])
                }}
                {{ Form::submit('Filter', [ 'class' => "btn btn-primary" ]) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>

    @include('admin.accounts.partials.list')
@stop