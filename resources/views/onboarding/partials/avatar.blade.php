<div class="onboarding-avatar" id='signup-onboarding-avatar'>
    <div class="onboarding-image-wrapper">
        <div class="loading-icon loading-icon-center"></div>
        @if ($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="">
        @else
            <img src="/images/cl-avatar2.png" alt="#">
        @endif
    </div>
    <label for="upload" class="onboarding-avatar-button">
        <i class="icon-add"></i>
        <input id="upload" name='avatar' type="file">
        <span>Upload Avatar</span>
    </label>
</div>