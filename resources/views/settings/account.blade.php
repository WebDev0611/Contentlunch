@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        {!!
            Form::model($account, [
                'url' => route('settingsAccount'),
                'files' => 'true',
                'id' => 'profile_settings'
            ])
        !!}

        @include('settings.partials.sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                @include('settings.partials.navigation')
            </div>

            @include('elements.freemium-alert')

            <div class="panel-container">

            @if ($errors->any())
                    <div class="alert alert-danger" id="formError">
                        <p><strong>Oops! We had some errors:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8 col-md-offset-1">

                        <div class="row">

                            <div class="col-md-4 vertically-middle">
                                <div class="onboarding-avatar" id='settings-avatar'>
                                    <div class="loading-icon loading-icon-center"></div>
                                    <img src="{{ $account->present()->account_image }}" alt="#">

                                    <label for="upload" class="onboarding-avatar-button">
                                        <i class="icon-add"></i>
                                        <input id="upload" name='avatar' type="file">
                                        <span>Upload Avatar</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 vertically-middle">
                                <div class="input-form-group">
                                    <label for="#">ACCOUNT NAME</label>
                                    {!!
                                        Form::text(
                                            'name',
                                            $account->name, [ 'class' => 'input' ]
                                        )
                                    !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-md-offset-7">
                                <div class="input-form-group">
                                    <button type='submit' class="button button-extend">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-content-box collaborators-list-container height-double">

            </div>

        </div>
        {!! Form::close() !!}
    </div>
</div>
@stop

@section('scripts')
<script src="{{ elixir('js/account-settings.js', null) }}"></script>
<script src="{{ elixir('js/avatar_view.js', null) }}"></script>
<script>
    var view = new AvatarView({ el: '#settings-avatar' });
</script>
<script>
    $(function(){
        //tasks
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }
    });
</script>
@stop