<div class="input-form-group">
    {{ Form::label('name', 'Full Name') }}
    {{ Form::text('name', Input::old('name'), ['placeholder' => 'your name', 'class' => 'input']) }}
</div>
<div class="input-form-group">
    {{ Form::label('email', 'Email Address') }}
    {{ Form::text('email', Input::old('email', $guestEmail), ['placeholder' => 'email', 'class' => 'input']) }}
</div>
<div class="input-form-group">
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password', ['placeholder' => 'password','class' => 'input']) }}
    <div class="input-strength-indicator">
        <span style="width: 30%;"></span>
    </div>
    <p class="onboarding-notification-text">
        <i class="icon-notification"></i>
        Password should contain minimum 8 characters, including alphanumeric,
        special and both upper and lower case
    </p>
</div>
<div class="input-form-group">
    {{ Form::password('password_confirmation', ['placeholder' => 'repeat password','class' => 'input']) }}
</div>