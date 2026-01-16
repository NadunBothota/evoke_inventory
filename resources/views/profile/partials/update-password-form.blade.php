<section>
    <header>
        <h5 class="card-title">
            {{ __('Update Password') }}
        </h5>

        <p class="text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input type="password" class="form-control" name="current_password" id="update_password_current_password" autocomplete="current-password">
             @error('current_password', 'updatePassword')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input type="password" class="form-control" name="password" id="update_password_password" autocomplete="new-password">
             @error('password', 'updatePassword')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" class="form-control" name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password">
             @error('password_confirmation', 'updatePassword')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p class="ms-3 text-muted">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>