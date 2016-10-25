@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel clearfix">
        {!!
            Form::model($user, [
                    'url' => route('settingsUpdate'),
                    'files' => 'true'
                ])
        !!}
        @include('settings.partials.profile_sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                <!-- navigation -->
                @include('settings.partials.navigation')
            </div>
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
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-form-group text-right">
                            <label for="#" class="checkbox-ios">
                                <span>Account Active</span>
                                <input type="checkbox">
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <!-- <div class="col-md-12"> -->
                                    <div class="input-form-group">
                                        <label for="#">FULL NAME</label>
                                        {!!
                                            Form::text(
                                                'name',
                                                @isset($user) ? $user->name : '',
                                                [
                                                    'class' => 'input',
                                                    'placeholder' => 'Name Surname'
                                                ]
                                            )
                                        !!}
                                    </div>
                                <!-- </div> -->
                                <!-- <div class="col-md-12"> -->
                                    <div class="input-form-group">
                                        <label for="#">ACCOUNT NAME</label>
                                        {!!
                                            Form::text('account_name', $user->account->name,
                                                [
                                                    'class'=> 'input',
                                                    'placeholder' => 'Account Name'
                                                ])
                                        !!}
                                    </div>
                                <!-- </div> -->
                            </div>
                            <div class="col-md-4">
                                <div class="onboarding-avatar" id='settings-avatar'>
                                    <div class="loading-icon loading-icon-center"></div>
                                    @if ($user->profile_image)
                                        <img src="{{ $user->profile_image }}" alt="#">
                                    @else
                                        <img src="/images/avatar.jpg" alt="#">
                                    @endif
                                    <label for="upload" class="onboarding-avatar-button">
                                        <i class="icon-add"></i>
                                        <input id="upload" name='avatar' type="file">
                                        <span>Upload Avatar</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-form-group">
                            <label for="#">ADDRESS</label>
                            {!!
                                Form::text(
                                    'address',
                                    $user->address,
                                    [
                                        'class' => 'input',
                                        'placholder' => 'Account holder address'
                                    ]
                                )
                            !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="city">CITY</label>
                                    {!!
                                        Form::text(
                                            'city',
                                            $user->city,
                                            [
                                                'class' => 'input',
                                                'placeholder' => 'Houston'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="country_code">COUNTRY</label>
                                    <div class="select">
                                        {!!
                                            Form::select(
                                                'country_code',
                                                $countries,
                                                $user->country_code,
                                                [ 'id' => 'country-selector' ]
                                            )
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-delimiter">
                            <span>
                                <em>Contacts</em>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">PHONE NUMBER</label>
                                    {!!
                                        Form::text(
                                            'phone',
                                            $user->phone ? $user->phone : '',
                                            [
                                                'class' => 'input',
                                                'placholder' => '+1 212 123455'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-form-group">
                                    <label for="#">EMAIL ADDRESS</label>
                                    {!!
                                        Form::text(
                                            'email',
                                            @isset($user) ? $user->email : '',
                                            [
                                                'class' => 'input',
                                                'placeholder' => 'Your email here'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-delimiter">
                            <span>
                                <em>Profile Picture</em>
                            </span>
                        </div>


                        <div class="input-form-group">
                            <div class="fileupload">
                                <i class="icon-content picto"></i>
                                <p class="msgtitle">Click to upload your profile picture</p>
                                <input type="file" class="input input-upload" name="avatar">
                            </div>
                        </div>
                        -->

                        <div class="input-form-group">
                            <button type='submit' class="button button-extend">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@stop

@section('scripts')
<script src="/js/avatar_view.js"></script>
<script>

    var view = new AvatarView({ el: '#settings-avatar' });

    function fileUpload(formData) {
        return $.ajax({
            type: 'post',
            url: 'signup/photo_upload',
            data: formData,
            processData: false,
            contentType: false
        });
    }

</script>
@stop