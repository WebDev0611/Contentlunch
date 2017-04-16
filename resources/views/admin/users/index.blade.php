@extends('admin.layouts.master')

@section('content')
    @include('admin.partials.header', [
        'title' => 'Users',
        'breadcrumbs' => [
            [ 'name' => 'Users', 'url' => route('admin_users.index') ],
        ]
    ])

    <div class="wrapper wrapper-content animated fadeInRight">

        @include('admin.partials.flash')
        @include('admin.partials.errors')

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
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Accounts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->present()->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->accounts as $account)
                            <span class="label {{ $account->isAgencyAccount() ? 'label-info' : '' }}">{{ $account->name }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@stop